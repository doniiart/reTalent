import os
import re

base_dir = "e:/project new/retalent/resources/views"

def fix_routes(content):
    # employees
    content = re.sub(r"route\('([a-z]+)\.employees\.update'\)", r"route('\1.employees.update', $employee)", content)
    content = re.sub(r"route\('([a-z]+)\.employees\.destroy'\)", r"route('\1.employees.destroy', $employee)", content)
    content = re.sub(r"route\('([a-z]+)\.employees\.show'\)", r"route('\1.employees.show', $employee)", content)
    
    # leave
    content = re.sub(r"route\('([a-z]+)\.leave\.update_status'\)", r"route('\1.leave.update_status', $leave)", content)
    
    # payroll
    content = re.sub(r"route\('([a-z]+)\.payroll\.update'\)", r"route('\1.payroll.update', $payroll)", content)
    content = re.sub(r"route\('([a-z]+)\.payroll\.download'\)", r"route('\1.payroll.download', $payroll)", content)
    
    # settings
    content = re.sub(r"route\('([a-z]+)\.settings\.departments\.destroy'\)", r"route('\1.settings.departments.destroy', $department)", content)
    content = re.sub(r"route\('([a-z]+)\.settings\.positions\.destroy'\)", r"route('\1.settings.positions.destroy', $position)", content)
    content = re.sub(r"route\('([a-z]+)\.settings\.job_types\.destroy'\)", r"route('\1.settings.job_types.destroy', $jobType)", content)
    
    return content

count = 0
for root, dirs, files in os.walk(base_dir):
    for file in files:
        if file.endswith('.blade.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
                
            new_content = fix_routes(content)
            
            if new_content != content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                count += 1
                print(f"Fixed {filepath}")

print(f"Fixed routes in {count} files.")
