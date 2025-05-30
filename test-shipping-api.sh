#!/bin/bash

echo "=========================================="
echo "TIKI Fish Shipping Calculator Test Suite"
echo "=========================================="
echo ""

echo "1. Testing API Connection..."
curl -s -X GET "http://localhost:8000/test-komerce" | jq -r '.success // "Failed"'
echo ""

echo "2. Testing Address Search (Jakarta)..."
SEARCH_RESULT=$(curl -s -X GET "http://localhost:8000/public/search-alamat?search=jakarta" | jq -r '.count // 0')
echo "Found $SEARCH_RESULT addresses"
echo ""

echo "3. Testing Address Search (Bandung)..."
SEARCH_RESULT2=$(curl -s -X GET "http://localhost:8000/public/search-alamat?search=bandung" | jq -r '.count // 0')
echo "Found $SEARCH_RESULT2 addresses"
echo ""

echo "4. Testing Domestic Cost Calculation (Backend Test)..."
COST_TEST=$(curl -s -X GET "http://localhost:8000/test-domestic-cost" | jq -r '.success // false')
echo "Domestic Cost Test: $COST_TEST"
echo ""

echo "5. Sample Shipping Costs (Jakarta to Bandung, 1kg):"
curl -s -X GET "http://localhost:8000/test-domestic-cost" | jq -r '.response_data.data[] | "\(.service): Rp \(.cost) (\(.etd))"' | head -6
echo ""

echo "=========================================="
echo "Frontend Testing Instructions:"
echo "=========================================="
echo "1. Open: http://localhost:8000/shipping-calculator"
echo "2. Test address search by typing city names"
echo "3. Try these test routes:"
echo "   - Jakarta → Surabaya (1kg)"
echo "   - Bandung → Medan (2kg)"
echo "   - Yogyakarta → Balikpapan (1.5kg)"
echo ""
echo "Expected Results:"
echo "- TIKI courier services with costs 10,000-15,000 IDR"
echo "- Filtered out motorcycle/trucking services"
echo "- Fastest service: ONS (15,000 IDR, 1 day)"
echo "- Cheapest service: ECO (10,000 IDR, 5 days)"
echo ""
echo "API Endpoints Available:"
echo "- GET  /public/search-alamat?search={city}"
echo "- POST /web/cek-ongkir (with CSRF token)"
echo "- POST /api/cek-ongkir (with throttle: 5 req/min)"
echo "- GET  /test-domestic-cost (demo endpoint)"
echo ""
echo "=========================================="
