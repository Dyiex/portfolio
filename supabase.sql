-- Run this once in Supabase: SQL Editor > New query > paste > Run.
create table if not exists public.messages (
  id bigint generated always as identity primary key,
  name text not null check (char_length(name) between 1 and 100),
  email text not null check (char_length(email) between 3 and 150),
  company text not null default '' check (char_length(company) <= 150),
  inquiry_type text not null default 'Something else' check (char_length(inquiry_type) <= 30),
  subject text not null check (char_length(subject) between 1 and 150),
  body text not null check (char_length(body) between 1 and 5000),
  is_read boolean not null default false,
  created_at timestamptz not null default now(),
  updated_at timestamptz
);
alter table public.messages enable row level security;

-- Visitors (anon) can only ADD messages. They cannot read, edit or delete any.
create policy "Visitors can send messages" on public.messages for insert to anon with check (is_read = false);
-- Only the logged-in admin can read, update and delete.
create policy "Admin can read" on public.messages for select to authenticated using (true);
create policy "Admin can update" on public.messages for update to authenticated using (true) with check (true);
create policy "Admin can delete" on public.messages for delete to authenticated using (true);

grant insert on public.messages to anon;
grant select, update, delete on public.messages to authenticated;
