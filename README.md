## How to locally start the app
Check if you have symfony installed.
If you are missing the symfony:

``brew install symfony-cli/tap/symfony-cli``

Check if the symfony is installed:

``symfony -v``

If it is still missing add path to your PATH file: .zshrc or .bashrc

Run symfony locally:

``symfony serve``

``symfony server:start``

If you do not want to have or use symfony server (use PHP built-in server):

``php -S localhost:8000 -t public``

If you are changing FE of the application - you must use:
``php bin/console tailwind:build``
for rebuilding the tailwind classes and css.

For docker containers to run:
``docker compose up``