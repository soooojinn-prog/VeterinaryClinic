$src = "$PSScriptRoot\wp-content\themes\doduri"
$dst = "C:\Users\tbi03u.SSG\Local Sites\doduri\app\public\wp-content\themes\doduri"

Get-ChildItem $src -Recurse -File | ForEach-Object {
    $rel  = $_.FullName.Substring($src.Length)
    $dest = $dst + $rel
    $dir  = Split-Path $dest
    if (!(Test-Path $dir)) { New-Item $dir -ItemType Directory -Force | Out-Null }
    Copy-Item $_.FullName $dest -Force
}
Write-Host "synced to LocalWP"
