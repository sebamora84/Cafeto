
_file="/var/www/html/dbbackup/dbrestore.sql"
echo "Starting restore from $_file..."
sudo mysql -u cafeto -pcafeto248! < $_file
echo "Finish restore from $_file"
