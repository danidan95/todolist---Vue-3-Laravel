Hi guys, 

Dani Dan, here! ->  https://www.linkedin.com/in/daniel-dan-57b910179/

There are 2 ways to experience this particular app:
1. Frontend ONLY (Vue 3)
2. Frontend (Vue 3) + Backend (Laravel 9) + DB (MySQL)

Steps to start this project:

1. clone repo: https://github.com/danidan95/todolist---Vue-3-Laravel
2. Run: npm install
3. Start your MySQL DB (xampp)
4. Run: php artisan migrate Make migration to Item , to connect it to your database and make the corresponding tables .
        if step 4 does not work set up manually a local db named "neptodolist" and a table named "tasklist" and WITHOUT password (see .env for details AND see Model <resources\assets\db_model_structure.png>
5. Run: npm run dev
6. Access your localhost:8000 or 127.0.0.1:8000

#DOCS / Refferences

This assignment was not done from scratch by me BUT it has some improvements/features arhitectured by myself, and i had also the indirect help of other guys, which have done this kind of project way ahead of me:

-> https://github.com/baratahmed/Scrypster-Laravel-Vue-TODO-Completed
-> https://github.com/shijiezhou1/vue3-todo
-> https://aalhommada.medium.com/make-todo-list-app-with-laravel-vue-js-and-bootstrap-10dfb4ed7563
-> https://dev.to/burakgur/create-todo-app-with-vue-3-composition-api-1ok7
-> https://github.com/nodenacci/laravel-vuejs-todolist
-> https://github.com/TylerPottsDev/yt-vue-todo-2022

Also have had documented from Vue3 and Laravel, PHP, Composer official Docs:

-> https://laravel.com/docs/9.x
-> https://www.php.net/docs.php
-> https://vuejs.org/guide/introduction.html
-> https://getcomposer.org/download/
-> https://vegibit.com/getting-your-database-set-up-for-use-with-laravel/