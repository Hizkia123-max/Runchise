$ftpServer = "ftp://ftpupload.net/htdocs"
$username = "if0_42307439"
$password = "Bejoterus"

$filesToUpload = @(
    "app/Modules/POS/Views/terminal.php",
    "app/Views/partials/sidebar.php",
    "app/Config/Routes.php",
    "app/Controllers/Home.php",
    "app/Views/activity.php"
)

function Upload-File {
    param (
        [string]$localPath,
        [string]$remotePath
    )

    Write-Host "Uploading $localPath to $remotePath..."
    
    if (!(Test-Path $localPath)) {
        Write-Host "File not found locally: $localPath"
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
        Write-Host "Success: $($response.StatusDescription)" -ForegroundColor Green
        $response.Close()
    }
    catch {
        Write-Host "Failed to upload $localPath. Error: $_" -ForegroundColor Red
        if ($ftpStream) { $ftpStream.Close() }
        if ($fileStream) { $fileStream.Close() }
    }
}

$baseDir = "c:\Users\HP\Documents\Runchise-main"

foreach ($file in $filesToUpload) {
    Upload-File -localPath "$baseDir\$file" -remotePath $file
}

$imagesDir = "$baseDir\public\images\products"
if (Test-Path $imagesDir) {
    $images = Get-ChildItem -Path $imagesDir -Filter "*.png"
    foreach ($img in $images) {
        $remoteImgPath = "public/images/products/" + $img.Name
        Upload-File -localPath $img.FullName -remotePath $remoteImgPath
    }
}
