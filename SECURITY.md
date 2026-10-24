# Deployment configuration

Set MYSS_DB_ENDPOINT, MYSS_DB_USER and MYSS_DB_PASSWORD outside source. Use a limited database identity and a private/TLS-protected endpoint. Rotate the previously committed credential if it was ever active; source removal does not revoke historical credentials.

User content is stored as text and must be escaped in every HTML context. Never render post or comment markup directly.
