import re

files = set([line.strip() for line in open('files_to_sync.txt', encoding='utf-16').read().splitlines() if line.strip()])
files.add('app/Modules/Authentication/Views/login.php')
files.add('app/Views/dashboard.php')
files.add('app/Modules/Authentication/Config/Routes.php')
files.add('app/Modules/Authentication/Controllers/AuthController.php')

content = open('auto_sync_web.ps1', encoding='utf-8').read()
new_array = ',\n'.join([f'    "{f}"' for f in sorted(list(files))])

new_content = re.sub(r'\$filesToUpload = @\([\s\S]*?\)', f'$filesToUpload = @(\n{new_array}\n)', content)
open('auto_sync_web.ps1', 'w', encoding='utf-8').write(new_content)
