RewriteEngine On

# Retain the "/cvv" prefix and redirect requests
RewriteBase /cvv

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]