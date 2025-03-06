# QuizQuest - Trivia Game App

## Introduction
**QuizQuest** is a mobile trivia game developed using **Android Studio (Java)** for the frontend and **PHP** for the backend. The application allows users to log in, choose from six question categories, and answer 10 multiple-choice questions per round. A web-based admin control panel is also included for managing the questions.

## Features
- **User Authentication:** Sign up and log in to play the game.
- **Category Selection:** Users choose from six trivia categories.
- **Quiz Gameplay:** Each round contains 10 multiple-choice questions.
- **Progress Tracking:** A progress bar updates when a correct answer is selected.
- **Leaderboard:** Players can compare their scores with others.
- **Admin Portal:** Allows administrators to manage the database of questions.

## How to Use
### User Side
1. **Login / Signup:** Users must create an account or log in.
2. **Select a Category:** Choose from Science, Geography, History, Football, Technology, or Entertainment.
3. **Answer Questions:** Each round consists of 10 questions with three answer choices.
4. **Scoring:** The progress bar increments with each correct answer.
5. **View Results:** After answering all 10 questions, the score is displayed.
6. **Leaderboard:** Users can view the top scores per category.
7. **Replay Option:** Players can start a new round from the category selection screen.

### Admin Panel
- Admins log in using default credentials.
- The control panel allows adding and deleting questions.
- Questions are categorized and stored in a database.
- Admins can manage the entire question set dynamically.

## Database Schema
- triviagame.sql found under SQL folder in the repository 
# Installation Instructions

## Prerequisites
- Android Studio installed with Java SDK.
- PHP and MySQL (using XAMPP or WAMP).
- A web server to host the backend files.

## Steps to Set Up

1. Clone the repository:
    ```sh
    git clone https://github.com/yourusername/QuizQuest.git
    ```
2. Import the Android project into Android Studio.
3. Set up a MySQL database using the provided schema.
4. Place the PHP backend files in a web server directory (e.g., `htdocs` in XAMPP).
5. Update API endpoints in the Android application as needed.
6. Run the app in an emulator or a physical device.

---

# Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature.
3. Make your changes and commit them.
4. Push the branch and submit a pull request.

---

# License

This project is licensed under the MIT License.
