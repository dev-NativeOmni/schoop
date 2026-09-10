## 2026-09-10 - Removed storage of plaintext passwords
**Vulnerability:** The application was storing users' plaintext passwords (encrypted via `Crypt::encryptString`) in a `password_plain` column and displaying them in the UI to super admins.
**Learning:** This is a severe security risk. Passwords should never be reversible. A compromised database or application key would expose all user passwords.
**Prevention:** Only store securely hashed passwords (e.g., using bcrypt/argon2 via `Hash::make`). Never implement functionality to view user passwords.
