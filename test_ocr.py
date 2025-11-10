import pytesseract
import cv2
import numpy as np
from PIL import Image, ImageDraw, ImageFont
import json

# Set Tesseract path
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

def create_test_image():
    """Create a test image with payment proof text."""
    # Create a white image
    img = Image.new('RGB', (600, 400), color='white')
    draw = ImageDraw.Draw(img)

    # Try to use a font, fallback to default if not available
    try:
        font = ImageFont.truetype("arial.ttf", 20)
        small_font = ImageFont.truetype("arial.ttf", 16)
    except:
        font = ImageFont.load_default()
        small_font = ImageFont.load_default()

    # Draw text
    draw.text((50, 50), "GCash Payment", fill='black', font=font)
    draw.text((50, 90), "Reference No: 1234567890", fill='black', font=small_font)
    draw.text((50, 120), "Name: John Dela Cruz", fill='black', font=small_font)
    draw.text((50, 150), "Mobile: +639171234567", fill='black', font=small_font)
    draw.text((50, 180), "Date: Nov 10, 2025 2:45 PM", fill='black', font=small_font)

    # Add some noise to simulate real OCR conditions
    img_array = np.array(img)
    noise = np.random.normal(0, 25, img_array.shape).astype(np.uint8)
    noisy_img = cv2.add(img_array, noise)

    return Image.fromarray(noisy_img)

def test_ocr():
    """Test the OCR extraction with the created image."""
    # Create test image
    test_img = create_test_image()
    test_img.save('test_payment_proof.png')

    # Extract text using OCR
    text = pytesseract.image_to_string(test_img)
    print("Raw OCR Text:")
    print("=" * 50)
    print(text)
    print("=" * 50)

    # Extract information
    result = {
        "reference_id": extract_reference_id(text),
        "name": extract_name(text),
        "contact_number": extract_contact_number(text),
        "date_time": extract_date_time(text)
    }

    print("\nExtracted Information:")
    print(json.dumps(result, indent=2))

    return result

def extract_reference_id(text):
    import re
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
    import re
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
            name = re.sub(r'\s+', ' ', name)
            return name
    return ""

def extract_contact_number(text):
    import re
    patterns = [
        r'(\+63\d{10})',
        r'(09\d{9})',
        r'(63\d{10})',
        r'(\d{11})'
    ]

    for pattern in patterns:
        matches = re.findall(pattern, text)
        for match in matches:
            digits = re.sub(r'\D', '', match)
            if 10 <= len(digits) <= 13:
                return match
    return ""

def extract_date_time(text):
    import re
    date_patterns = [
        r'(\d{1,2})[/-](\d{1,2})[/-](\d{4})',
        r'(\w{3})\s+(\d{1,2}),?\s+(\d{4})',
        r'(\d{4})[/-](\d{1,2})[/-](\d{1,2})'
    ]

    time_patterns = [
        r'(\d{1,2}):(\d{2})(?::(\d{2}))?\s*(AM|PM|am|pm)?',
        r'(\d{1,2})\s*:\s*(\d{2})\s*(AM|PM|am|pm)'
    ]

    date_str = ""
    time_str = ""

    for pattern in date_patterns:
        match = re.search(pattern, text, re.IGNORECASE)
        if match:
            if len(match.groups()) == 3:
                if re.match(r'\w{3}', match.group(1)):
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
                    part1, part2, part3 = match.groups()
                    if int(part1) > 12:
                        date_str = f"{part3}-{part2.zfill(2)}-{part1.zfill(2)}"
                    else:
                        date_str = f"{part3}-{part1.zfill(2)}-{part2.zfill(2)}"
            break

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

if __name__ == "__main__":
    test_ocr()
