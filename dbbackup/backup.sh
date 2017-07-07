_now=$(date +"%Y%m%d%H%M%S")
_file="/var/www/html/dbbackup/dbbackup.$_now.sql"
echo "Starting backup to $_file..."
mysqldump -u cafeto -pcafeto248! --single-transaction --no-create-db  --databases cafeto > "$_file"
echo "Finish backup to $_file"

