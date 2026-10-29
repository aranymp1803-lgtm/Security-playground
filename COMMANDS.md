# Project commands and checks

These are the commands recorded or used during the project. Run them on the VM named in each section.

## Identify network addresses

On any Ubuntu VM:

```bash
ip addr
```

## Web Server (`10.61.0.247`)

Check Apache:

```bash
sudo systemctl status apache2
```

Check response locally:

```bash
curl http://localhost/
curl -I http://localhost/
```

Check the web root:

```bash
ls -l /var/www/html/
```

Check listening TCP ports/services:

```bash
sudo ss -lntp
```

Check from the Attacker/Client VM:

```bash
curl -I http://10.61.0.247/
curl http://10.61.0.247/
ping -c 4 10.61.0.247
```

## Database Server (`10.61.0.224`)

Check MariaDB:

```bash
sudo systemctl status mariadb
```

Check listening TCP ports/services:

```bash
sudo ss -lntp
```

Open the MariaDB shell:

```bash
sudo mariadb
```

At the MariaDB prompt, inspect permissions:

```sql
SHOW GRANTS FOR 'webapp'@'10.61.0.247';
```

Inspect the table and test account:

```sql
USE login_app;
SHOW TABLES;
DESCRIBE users;
SELECT id, username FROM users;
```

Exit MariaDB:

```sql
exit;
```

From the Web Server, test the remote database connection:

```bash
mariadb -h 10.61.0.224 -u webapp -p login_app
```

Enter the lab database password when prompted. Avoid putting passwords directly into shell commands or screenshots.

## HTTP login tests (Attacker/Client VM)

Correct credentials:

```bash
curl -s -X POST -d "username=student&password=test123" http://10.61.0.247/
```

Incorrect password:

```bash
curl -s -X POST -d "username=student&password=wrongpassword" http://10.61.0.247/
```

Empty fields:

```bash
curl -s -X POST -d "username=&password=" http://10.61.0.247/
```

SQL injection regression test against the fixed application:

```bash
curl -i -X POST \
  --data-urlencode "username=student' OR '1'='1' -- " \
  --data-urlencode "password=wrongpassword" \
  http://10.61.0.247/
```

Repeated incorrect login attempts:

```bash
for i in 1 2 3 4 5; do
  curl -s -X POST -d "username=student&password=wrongpassword" http://10.61.0.247/ | grep -o "Access denied!"
done
```

## PHP / SQL injection fix to explain in the report

The fixed application uses a parameterized query:

```php
$stmt = $conn->prepare(
    "SELECT id, password FROM users WHERE username = ?"
);
$stmt->bind_param("s", $username);
$stmt->execute();
```

The `?` placeholder keeps the supplied username separate from the SQL command structure. This prevents the username field from changing the query's logic.

## Firewall check

On the relevant Ubuntu VM:

```bash
sudo ufw status verbose
```

Record the actual result. If it says `Status: inactive`, do not report UFW as active. Also inspect the EduCloud network/security-group rules in the platform.

## Notes on evidence

- Screenshot commands together with their output.
- Redact passwords or secrets before committing screenshots to GitHub.
- Do not claim firewall changes, rate limiting, or other protections unless you actually configured and tested them.
