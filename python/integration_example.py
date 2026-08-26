import subprocess
import json
import os

def process_payment_proof(image_path):
    """
    Process a payment proof image using the OCR processor.

    Args:
        image_path (str): Path to the payment proof image

    Returns:
        dict: Extracted payment information
    """
    try:
        # Check if image exists
        if not os.path.exists(image_path):
            return {"error": f"Image file not found: {image_path}"}

        # Run the OCR processor
        result = subprocess.run(
            ['python', 'ocr_processor.py', image_path],
            capture_output=True,
            text=True,
            cwd=os.path.dirname(__file__)
        )

        if result.returncode != 0:
            return {"error": f"OCR processing failed: {result.stderr}"}

        # Parse the JSON output
        try:
            data = json.loads(result.stdout.strip())
            return data
        except json.JSONDecodeError:
            return {"error": f"Invalid JSON output: {result.stdout}"}

    except Exception as e:
        return {"error": str(e)}

def validate_payment_data(data):
    """
    Validate the extracted payment data.

    Args:
        data (dict): Extracted payment information

    Returns:
        tuple: (is_valid, errors)
    """
    errors = []

    if "error" in data:
        return False, [data["error"]]

    # Check required fields
    required_fields = ["reference_id", "name", "contact_number", "date_time"]
    for field in required_fields:
        if field not in data or not data[field]:
            errors.append(f"Missing or empty field: {field}")

    # Validate reference ID (should be 10 digits)
    if data.get("reference_id") and not data["reference_id"].isdigit():
        errors.append("Reference ID should contain only digits")
    elif data.get("reference_id") and len(data["reference_id"]) != 10:
        errors.append("Reference ID should be 10 digits")

    # Validate contact number (should be Philippine mobile number)
    if data.get("contact_number"):
        contact = data["contact_number"]
        if not (contact.startswith("+63") or contact.startswith("09")):
            errors.append("Contact number should be a Philippine mobile number (+63 or 09 prefix)")
        digits = ''.join(filter(str.isdigit, contact))
        if len(digits) < 10 or len(digits) > 13:
            errors.append("Contact number should have 10-13 digits")

    # Validate date_time format
    if data.get("date_time"):
        import re
        if not re.match(r'\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}', data["date_time"]):
            errors.append("Date/time should be in YYYY-MM-DD HH:MM:SS format")

    return len(errors) == 0, errors

# Example usage
if __name__ == "__main__":
    # Example with the test image
    image_path = "test_payment_proof.png"

    print("Processing payment proof...")
    result = process_payment_proof(image_path)

    print("\nRaw OCR Result:")
    print(json.dumps(result, indent=2))

    # Validate the result
    is_valid, errors = validate_payment_data(result)

    print(f"\nValidation: {'PASS' if is_valid else 'FAIL'}")
    if not is_valid:
        print("Errors:")
        for error in errors:
            print(f"  - {error}")

    # Example of how this would be used in a Laravel controller
    print("\n" + "="*50)
    print("LARAVEL INTEGRATION EXAMPLE")
    print("="*50)

    if is_valid:
        print("""
In your Laravel controller, you could use it like this:

```php
public function processPaymentProof(Request $request)
{
    $image = $request->file('payment_proof');

    // Save the uploaded image temporarily
    $tempPath = $image->store('temp');

    // Process with OCR
    $ocrResult = process_payment_proof(storage_path('app/' . $tempPath));

    // Validate the result
    list($isValid, $errors) = validate_payment_data($ocrResult);

    if (!$isValid) {
        return response()->json([
            'success' => false,
            'errors' => $errors
        ], 400);
    }

    // Save to database
    $payment = Payment::create([
        'reference_id' => $ocrResult['reference_id'],
        'customer_name' => $ocrResult['name'],
        'contact_number' => $ocrResult['contact_number'],
        'payment_date' => $ocrResult['date_time'],
        'status' => 'verified'
    ]);

    // Clean up temp file
    Storage::delete($tempPath);

    return response()->json([
        'success' => true,
        'payment' => $payment
    ]);
}
```
""")
    else:
        print("Cannot show Laravel integration example due to validation errors.")
