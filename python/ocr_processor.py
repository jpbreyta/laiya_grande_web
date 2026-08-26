import pytesseract
import cv2
import re
from PIL import Image
import json

# Set Tesseract path
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

# Test Tesseract installation
try:
    version = pytesseract.get_tesseract_version()
    print(f"Tesseract version: {version}")
except Exception as e:
    print(f"Tesseract error: {e}")

def extract_payment_info(image_path):
    """
    Extract payment information from OCR text of a payment proof image.
    """
    try:
        # Read image
        image = cv2.imread(image_path)
        if image is None:
            return {"error": "Could not read image"}

        # Convert to grayscale for better OCR
        gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

        # Apply thresholding to get better contrast
        _, thresh = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)

        # OCR the image
        text = pytesseract.image_to_string(thresh)

        # Extract information
        result = {
            "reference_id": extract_reference_id(text),
            "date_time": extract_date_time(text),
            "total_amount": extract_total_amount(text)
        }

        return result

    except Exception as e:
        return {"error": str(e)}

def extract_reference_id(text):
    """
    Extract reference/transaction ID from OCR text.
    """
    patterns = [
        r'Reference\s*No\.?\s*[:\-]?\s*([A-Za-z0-9\-]+)',
        r'Ref\s*No\.?\s*[:\-]?\s*([A-Za-z0-9\-]+)',
        r'Reference\s*ID\s*[:\-]?\s*([A-Za-z0-9\-]+)',
        r'Transaction\s*ID\s*[:\-]?\s*([A-Za-z0-9\-]+)',
        r'Transaction\s*No\.?\s*[:\-]?\s*([A-Za-z0-9\-]+)'
    ]

    for pattern in patterns:
        match = re.search(pattern, text, re.IGNORECASE)
        if match:
            return match.group(1).strip()

    return ""

def extract_name(text):
    """
    Extract sender/account holder name from OCR text.
    """
    # Look for patterns like "Name: John Doe" or "Sender: John Doe"
    patterns = [
        r'Name\s*[:\-]?\s*([A-Za-z\s]+)',
        r'Sender\s*[:\-]?\s*([A-Za-z\s]+)',
        r'Account\s*Holder\s*[:\-]?\s*([A-Za-z\s]+)',
        r'From\s*[:\-]?\s*([A-Za-z\s]+)'
    ]

    for pattern in patterns:
        match = re.search(pattern, text, re.IGNORECASE)
        if match:
            name = match.group(1).strip()
            # Clean up extra spaces
            name = re.sub(r'\s+', ' ', name)
            return name

    return ""

def extract_contact_number(text):
    """
    Extract mobile/phone number from OCR text.
    """
    # Philippine mobile number patterns
    patterns = [
        r'(\+63\d{10})',  # +639xxxxxxxxx
        r'(09\d{9})',     # 09xxxxxxxxx
        r'(63\d{10})',    # 639xxxxxxxxx
        r'(\d{11})'       # 10-13 digit numbers
    ]

    for pattern in patterns:
        matches = re.findall(pattern, text)
        for match in matches:
            # Validate length (10-13 digits)
            digits = re.sub(r'\D', '', match)
            if 10 <= len(digits) <= 13:
                return match

    return ""

def extract_date_time(text):
    """
    Extract date and time from OCR text and format as YYYY-MM-DD HH:MM:SS.
    """
    # Common date patterns
    date_patterns = [
        r'(\d{1,2})[/-](\d{1,2})[/-](\d{4})',  # MM/DD/YYYY or DD/MM/YYYY
        r'(\w{3})\s+(\d{1,2}),?\s+(\d{4})',     # Nov 10, 2025
        r'(\d{4})[/-](\d{1,2})[/-](\d{1,2})'   # YYYY/MM/DD
    ]

    time_patterns = [
        r'(\d{1,2}):(\d{2})(?::(\d{2}))?\s*(AM|PM|am|pm)?',  # HH:MM[:SS] [AM/PM]
        r'(\d{1,2})\s*:\s*(\d{2})\s*(AM|PM|am|pm)'          # HH : MM AM/PM
    ]

    date_str = ""
    time_str = ""

    # Extract date
    for pattern in date_patterns:
        match = re.search(pattern, text, re.IGNORECASE)
        if match:
            if len(match.groups()) == 3:
                # Handle different formats
                if re.match(r'\w{3}', match.group(1)):  # Month name format
                    month_name = match.group(1)
                    day = match.group(2)
                    year = match.group(3)
                    month_map = {
                        'jan': '01', 'feb': '02', 'mar': '03', 'apr': '04',
                        'may': '05', 'jun': '06', 'jul': '07', 'aug': '08',
                        'sep': '09', 'oct': '10', 'nov': '11', 'dec': '12'
                    }
                    month = month_map.get(month_name.lower()[:3], '01')
                    date_str = f"{year}-{month.zfill(2)}-{day.zfill(2)}"
                else:
                    # Numeric formats - assume MM/DD/YYYY or DD/MM/YYYY
                    part1, part2, part3 = match.groups()
                    if int(part1) > 12:  # Likely DD/MM/YYYY
                        date_str = f"{part3}-{part2.zfill(2)}-{part1.zfill(2)}"
                    else:  # Assume MM/DD/YYYY
                        date_str = f"{part3}-{part1.zfill(2)}-{part2.zfill(2)}"
            break

    # Extract time
    for pattern in time_patterns:
        match = re.search(pattern, text, re.IGNORECASE)
        if match:
            hour = int(match.group(1))
            minute = match.group(2)
            second = match.group(3) if match.group(3) else '00'
            am_pm = match.group(4) if len(match.groups()) >= 4 and match.group(4) else None

            if am_pm:
                if am_pm.upper() == 'PM' and hour != 12:
                    hour += 12
                elif am_pm.upper() == 'AM' and hour == 12:
                    hour = 0

            time_str = f"{hour:02d}:{minute}:{second}"
            break

    if date_str and time_str:
        return f"{date_str} {time_str}"
    elif date_str:
        return f"{date_str} 00:00:00"
    elif time_str:
        return f"0000-00-00 {time_str}"
    else:
        return ""

def extract_total_amount(text):
    """
    Extract total amount from OCR text.
    """
    patterns = [
        r'Total\s*[:\-]?\s*₱?\s*([0-9,]+\.?\d*)',
        r'Amount\s*[:\-]?\s*₱?\s*([0-9,]+\.?\d*)',
        r'₱\s*([0-9,]+\.?\d*)',
        r'PHP\s*([0-9,]+\.?\d*)',
        r'P\s*([0-9,]+\.?\d*)',
        r'Sent\s*[:\-]?\s*₱?\s*([0-9,]+\.?\d*)',
        r'Transfer\s*[:\-]?\s*₱?\s*([0-9,]+\.?\d*)'
    ]

    for pattern in patterns:
        match = re.search(pattern, text, re.IGNORECASE)
        if match:
            amount_str = match.group(1).replace(',', '')
            try:
                return float(amount_str)
            except ValueError:
                continue

    return 0.0

if __name__ == "__main__":
    import sys
    if len(sys.argv) != 2:
        print("Usage: python ocr_processor.py <image_path>")
        sys.exit(1)

    image_path = sys.argv[1]
    result = extract_payment_info(image_path)
    print(json.dumps(result, indent=2))
