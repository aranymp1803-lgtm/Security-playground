# Fontys Cybersecurity & Networking Project

A small virtual network with an Apache/PHP login application and a MariaDB database. The lab demonstrates a SQL injection authentication bypass and the use of a prepared statement to prevent it.

## VM addresses

| VM | Address | Role |
|---|---|---|
| Attacker / Client | `10.61.0.179` | Sends HTTP requests and runs tests |
| Web Server | `10.61.0.247` | Apache + PHP application |
| Database Server | `10.61.0.224` | MariaDB database |

## Repository structure

- `web/index.php` — current prepared-statement version of the login application.
- `database/setup.sql` — example database/schema/account setup. Review and adapt before running.
- `tests/http-tests.sh` — curl-based login and SQL injection regression tests.
- `COMMANDS.md` — VM setup checks, service checks, database checks, and evidence commands.
- `web/vulnerable-example.php` — illustrative vulnerable version for explaining the original lab issue. This is reconstructed, not guaranteed to be the exact historical file from the VM.

## Important lab notes

- The current fixed application still contains a plaintext-password fallback to keep the existing lab account `student` / `test123` working. This is **not suitable for production**. A final hardened version should migrate stored passwords to hashes and remove the fallback.
- Keep database credentials out of public GitHub repositories. The PHP example uses the lab credential previously recorded for this project; replace it with an environment variable or local config file before publishing.
- Do not publish real passwords, API keys, private IP information your course requires you to keep private, or screenshots containing secrets.
- The SQL setup script is a template. If the database/user already exists, don't blindly rerun it; it may fail or change existing state.
