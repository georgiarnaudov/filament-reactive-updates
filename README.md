## How to reproduce the strange reactive behavior?

1. Clone the repo
2. `mv .env.example .env`
3. Create a `database.sqlite` file in the database folder
4. Run the migrations with `--seed` in order to seed some sample attributes
5. Go to the admin panel and for try to create a new product. On the create page, shrink the height of your viewport a bit so that you can see the jumping effect when fields update.
