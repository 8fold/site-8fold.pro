# Processes

## HTTPS and SSL

All 8fold sites must use secure protocols by default.

1. [Add SSL encryption to domain](https://help.dreamhost.com/hc/en-us/articles/215089118-Adding-an-SSL-certificate-overview)
2. Update `.htaccess` file to include the Force HTTPS content.

```
# Force HTTPS
RewriteCond %{HTTPS} !=on
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301,NE]
Header always set Content-Security-Policy "upgrade-insecure-requests;"
```

## Continuous Deployment (website)

You may need to wait in between steps.

1. [Create FTP+SSH user](https://help.dreamhost.com/hc/en-us/articles/216385837-Creating-a-user-with-Shell-SSH-access) if you haven't already.
2. [Create subdomain](https://help.dreamhost.com/hc/en-us/articles/215457827-Adding-a-subdomain) and associate it with the FTP+SSH user—recommend: `deploy.{your domain}`
3. Launch a command prompt (Terminal for macOS, for example).
4. Enter: `ssh {SSH username}@{yourdomain}`
	1. You should be prompted to enter a password. Enter the password of the user.
	2. You should be connected to the server.
		- If no: try again
		- If yes:
			1. Enter: `ls`
			2. You should be presented with a list of directories, one of which has the same name as the subdomain: `deploy.{your domain}`
				- If the subdomain directory is not listed, wait.
6. Enter: `cd /path/to/domain`
	- You can start typing the directory's name and use the tab key to autocomplete.
7. Enter: `git clone https://github.com/path/to/deployer`
	1. You should be prompted to enter your GitHub username and access token; do so.
	2. The repository should be cloned into the subdomain directory.
8. [Change the web directory assigned](https://help.dreamhost.com/hc/en-us/articles/360041534491-Changing-the-web-directory-assigned-to-a-domain) to: `/subdomain/deployer/public`
9. Add HTTPS and SSL to the subdomain.
10. Create/Update 