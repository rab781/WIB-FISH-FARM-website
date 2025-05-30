# TIKI Fish Shipping Calculator Test Suite
# PowerShell Script for Windows

Write-Host "===========================================" -ForegroundColor Cyan
Write-Host "TIKI Fish Shipping Calculator Test Suite" -ForegroundColor Cyan
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "1. Testing API Connection..." -ForegroundColor Yellow
try {
    $response = Invoke-RestMethod -Uri "http://localhost:8000/test-komerce" -Method GET
    Write-Host "API Connection: $($response.success)" -ForegroundColor Green
} catch {
    Write-Host "API Connection: Failed" -ForegroundColor Red
}
Write-Host ""

Write-Host "2. Testing Address Search (Jakarta)..." -ForegroundColor Yellow
try {
    $searchResult = Invoke-RestMethod -Uri "http://localhost:8000/public/search-alamat?search=jakarta" -Method GET
    Write-Host "Found $($searchResult.count) addresses" -ForegroundColor Green
} catch {
    Write-Host "Address search failed" -ForegroundColor Red
}
Write-Host ""

Write-Host "3. Testing Address Search (Bandung)..." -ForegroundColor Yellow
try {
    $searchResult2 = Invoke-RestMethod -Uri "http://localhost:8000/public/search-alamat?search=bandung" -Method GET
    Write-Host "Found $($searchResult2.count) addresses" -ForegroundColor Green
} catch {
    Write-Host "Address search failed" -ForegroundColor Red
}
Write-Host ""

Write-Host "4. Testing Domestic Cost Calculation (Backend Test)..." -ForegroundColor Yellow
try {
    $costTest = Invoke-RestMethod -Uri "http://localhost:8000/test-domestic-cost" -Method GET
    Write-Host "Domestic Cost Test: $($costTest.success)" -ForegroundColor Green

    Write-Host ""
    Write-Host "5. Sample Shipping Costs (Jakarta to Bandung, 1kg):" -ForegroundColor Yellow
    foreach ($service in $costTest.response_data.data | Select-Object -First 6) {
        $cost = "{0:N0}" -f $service.cost
        Write-Host "   $($service.service): Rp $cost ($($service.etd))" -ForegroundColor White
    }
} catch {
    Write-Host "Domestic cost test failed" -ForegroundColor Red
}
Write-Host ""

Write-Host "===========================================" -ForegroundColor Cyan
Write-Host "Frontend Testing Instructions:" -ForegroundColor Cyan
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host "1. Open: http://localhost:8000/shipping-calculator" -ForegroundColor White
Write-Host "2. Test address search by typing city names" -ForegroundColor White
Write-Host "3. Try these test routes:" -ForegroundColor White
Write-Host "   - Jakarta → Surabaya (1kg)" -ForegroundColor Gray
Write-Host "   - Bandung → Medan (2kg)" -ForegroundColor Gray
Write-Host "   - Yogyakarta → Balikpapan (1.5kg)" -ForegroundColor Gray
Write-Host ""
Write-Host "Expected Results:" -ForegroundColor Yellow
Write-Host "- TIKI courier services with costs 10,000-15,000 IDR" -ForegroundColor White
Write-Host "- Filtered out motorcycle/trucking services" -ForegroundColor White
Write-Host "- Fastest service: ONS (15,000 IDR, 1 day)" -ForegroundColor White
Write-Host "- Cheapest service: ECO (10,000 IDR, 5 days)" -ForegroundColor White
Write-Host ""
Write-Host "API Endpoints Available:" -ForegroundColor Yellow
Write-Host "- GET  /public/search-alamat?search={city}" -ForegroundColor White
Write-Host "- POST /web/cek-ongkir (with CSRF token)" -ForegroundColor White
Write-Host "- POST /api/cek-ongkir (with throttle: 5 req/min)" -ForegroundColor White
Write-Host "- GET  /test-domestic-cost (demo endpoint)" -ForegroundColor White
Write-Host ""
Write-Host "===========================================" -ForegroundColor Cyan

# Optional: Auto-open browser
$openBrowser = Read-Host "Open shipping calculator in browser? (Y/N)"
if ($openBrowser -eq "Y" -or $openBrowser -eq "y") {
    Start-Process "http://localhost:8000/shipping-calculator"
}
