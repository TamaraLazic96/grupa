## How to locally start the app
Check if you have symfony installed.
If you are missing the symfony:

``brew install symfony-cli/tap/symfony-cli``

Check if the symfony is installed:

``symfony -v``

If it is still missing add path to your PATH file: .zshrc or .bashrc

Run symfony locally:

``symfony serve``

If you do not want to have or use symfony server (use PHP built-in server):

``php -S localhost:8000 -t public``

