# Portfolio website (static, runs on GitHub Pages)

index.html     home page: hire-me details, services, public contact form (no registration)
resume.html    printable resume (Download as PDF button)
admin.html     owner-only inbox: read, mark read, edit, delete messages
css/style.css  js/main.js (theme)  js/contact.js (send message)  js/admin.js (inbox)
js/config.js   your Supabase URL and anon key
supabase.sql   database table and security rules (run once in Supabase)

## Setup
1. Create a free project at supabase.com.
2. SQL Editor > paste supabase.sql > Run.
3. Authentication > Users > Add user (your email + password, tick auto-confirm).
   Then Authentication > Sign In / Providers > turn OFF "Allow new users to sign up".
4. Project Settings > API: copy the Project URL and the anon (publishable) key into js/config.js.
   Never put the service_role key anywhere in this folder.
5. Create a GitHub repository, upload everything in this folder, then Settings > Pages >
   Deploy from branch > main > / (root). Your site goes live at yourusername.github.io/repo-name.
