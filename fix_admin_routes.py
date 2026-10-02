import os
import re

# Fix web.php missing route
web_php_path = 'e:/project new/retalent/routes/web.php'
with open(web_php_path, 'r', encoding='utf-8') as f:
    web_php_content = f.read()

if "name('settings.attendance.update');" not in web_php_content[web_php_content.find('RUTE ADMIN'):]:
    # Insert it after settings.photo
    search_str = "Route::post('/settings/photo', [\\App\\Http\\Controllers\\Admin\\SettingsController::class, 'updatePhoto'])->name('settings.photo');"
    insert_str = "\n    Route::post('/settings/attendance', [\\App\\Http\\Controllers\\Admin\\SettingsController::class, 'updateAttendance'])->name('settings.attendance.update');"
    
    if search_str in web_php_content:
        web_php_content = web_php_content.replace(search_str, search_str + insert_str)
        with open(web_php_path, 'w', encoding='utf-8') as f:
            f.write(web_php_content)
        print("Added settings.attendance.update to admin routes in web.php")

# Fix attendance.export')]) }} error in all files
base_dir = 'e:/project new/retalent/resources/views'
count = 0
for root, dirs, files in os.walk(base_dir):
    for file in files:
        if file.endswith('.blade.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            new_content = re.sub(r"route\('([^']+?)\.attendance\.export'\)\]\)", r"route('\1.attendance.export')", content)
            # Just in case, let's also fix attendance.index
            new_content = re.sub(r"route\('([^']+?)\.attendance\.index'\)\]\)", r"route('\1.attendance.index')", new_content)
            
            if new_content != content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Fixed extra brackets in {filepath}")
                count += 1

print(f"Done fixing {count} view files.")
