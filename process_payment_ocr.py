#!/usr/bin/env python3
"""
Payment OCR Processor
Usage: python process_payment_ocr.py <image_path>
"""

import sys
import os
import json

# Add current directory to path to import ocr_processor
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from ocr_processor import extract_payment_info

def main():
    if len(sys.argv) != 2:
        print("Usage: python process_payment_ocr.py <image_path>")
        sys.exit(1)

    image_path = sys.argv[1]

    # Check if file exists
    if not os.path.exists(image_path):
        result = {"error": f"Image file does not exist: {image_path}"}
        print(json.dumps(result, indent=2))
        sys.exit(1)

    # Process the image
    result = extract_payment_info(image_path)
    print(json.dumps(result, indent=2))

if __name__ == "__main__":
    main()
