$ftpServer = "ftp://ftpupload.net/htdocs"
$username = "if0_42307439"
$password = "Bejoterus"

$filesToUpload = @(
    "app/Config/App.php",
    "app/Config/Routes.php",
    "app/Controllers/Home.php",
    "app/Database/Migrations/2026-06-01-000010_CreatePurchasingTables.php",
    "app/Database/Migrations/2026-06-01-000011_CreatePurchasePayments.php",
    "app/Database/Migrations/2026-07-22-000000_CreateMenusTable.php",
    "app/Database/Seeds/InitialDataSeeder.php",
    "app/Database/Seeds/MenuSeeder.php",
    "app/Models/MenuModel.php",
    "app/Modules/Authentication/Config/Routes.php",
    "app/Modules/Authentication/Controllers/AuthController.php",
    "app/Modules/Authentication/Views/login.php",
    "app/Modules/Inventory/Controllers/ProductController.php",
    "app/Modules/Inventory/Views/categories.php",
    "app/Modules/Inventory/Views/products.php",
    "app/Modules/Inventory/Views/promos.php",
    "app/Modules/POS/Controllers/POSController.php",
    "app/Modules/POS/Services/CheckoutService.php",
    "app/Modules/POS/Views/terminal.php",
    "app/Modules/Purchasing/Config/Routes.php",
    "app/Modules/Purchasing/Controllers/PurchasingController.php",
    "app/Modules/Purchasing/Views/purchase_order_form.php",
    "app/Modules/Purchasing/Views/purchase_orders.php",
    "app/Modules/Purchasing/Views/purchase_payments.php",
    "app/Modules/Report/Config/Routes.php",
    "app/Modules/Report/Controllers/ReportController.php",
    "app/Modules/Report/Views/sales_report_numeric.php",
    "app/Modules/Report/Views/sales_report_visual.php",
    "app/Views/activity.php",
    "app/Views/dashboard.php",
    "app/Views/partials/sidebar.php",
    "public/images/products/amd.png",
    "public/images/products/asus.png",
    "public/images/products/corsair.png",
    "public/images/products/dell.png",
    "public/images/products/lenovo.png",
    "public/images/products/logitech.png",
    "public/images/products/macbook.png",
    "public/images/products/nvidia.png",
    "public/images/products/samsung.png"
)

function Upload-File {
    param (
        [string]$localPath,
        [string]$remotePath
    )

    Write-Host "Mengupload $localPath ke $remotePath..."
    
    if (!(Test-Path $localPath)) {
        Write-Host "File tidak ditemukan di localhost: $localPath" -ForegroundColor Yellow
        return
    }

    try {
        $uri = "$ftpServer/$remotePath"
        $uri = $uri.Replace("\", "/")
        
        $ftpRequest = [System.Net.FtpWebRequest]::Create($uri)
        $ftpRequest.Credentials = New-Object System.Net.NetworkCredential($username, $password)
        $ftpRequest.Method = [System.Net.WebRequestMethods+Ftp]::UploadFile
        $ftpRequest.UsePassive = $true
        $ftpRequest.UseBinary = $true
        $ftpRequest.Timeout = 30000 # 30 seconds timeout
        $ftpRequest.KeepAlive = $false

        $fileStream = [System.IO.File]::OpenRead($localPath)
        $ftpStream = $ftpRequest.GetRequestStream()

        $buffer = New-Object byte[] 10240
        while (($read = $fileStream.Read($buffer, 0, $buffer.Length)) -gt 0) {
            $ftpStream.Write($buffer, 0, $read)
        }

        $ftpStream.Close()
        $fileStream.Close()
        
        $response = $ftpRequest.GetResponse()
        Write-Host "✅ Sukses: $($response.StatusDescription)" -ForegroundColor Green
        $response.Close()
    }
    catch {
        Write-Host "❌ Gagal upload $localPath. Error: $_" -ForegroundColor Red
        if ($ftpStream) { $ftpStream.Close() }
        if ($fileStream) { $fileStream.Close() }
    }
}

$baseDir = "c:\Users\HP\Documents\Runchise-main"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   AUTO SYNC LOCALHOST KE INFINITYFREE    " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

foreach ($file in $filesToUpload) {
    Upload-File -localPath "$baseDir\$file" -remotePath $file
}

# Upload Images (karena public/ dihapus di server, langsung ke images/products)
$imagesDir = "$baseDir\public\images\products"
if (Test-Path $imagesDir) {
    $images = Get-ChildItem -Path $imagesDir -Filter "*.png"
    foreach ($img in $images) {
        $remoteImgPath = "images/products/" + $img.Name
        Upload-File -localPath $img.FullName -remotePath $remoteImgPath
    }
}

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   PROSES SYNC SELESAI                    " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "Silakan tutup jendela ini."
Write-Host "Sync selesai tanpa menunggu input."

