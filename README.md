\documentclass{article}
\usepackage{hyperref}

\title{QuizQuest - Trivia Game App}
\author{}
\date{}

\begin{document}

\maketitle

\section*{Introduction}
\textbf{QuizQuest} is a mobile trivia game developed using \textbf{Android Studio (Java)} for the frontend and \textbf{PHP} for the backend. The application provides users with the ability to log in, choose from six question categories, and answer a set of 10 multiple-choice questions per round. A web-based admin control panel is also included for managing the questions.

\section*{Features}
\begin{itemize}
    \item \textbf{User Authentication:} Sign up and log in to play the game.
    \item \textbf{Category Selection:} Users choose from six trivia categories.
    \item \textbf{Quiz Gameplay:} Each round contains 10 multiple-choice questions.
    \item \textbf{Progress Tracking:} A progress bar updates when a correct answer is selected.
    \item \textbf{Leaderboard:} Players can compare their scores with others.
    \item \textbf{Admin Portal:} Allows administrators to manage the database of questions.
\end{itemize}

\section*{How to Use}
\subsection*{User Side}
\begin{enumerate}
    \item \textbf{Login / Signup:} Users must create an account or log in.
    \item \textbf{Select a Category:} Choose from Science, Geography, History, Football, Technology, or Entertainment.
    \item \textbf{Answer Questions:} Each round consists of 10 questions with three answer choices.
    \item \textbf{Scoring:} The progress bar increments with each correct answer.
    \item \textbf{View Results:} After answering all 10 questions, the score is displayed.
    \item \textbf{Leaderboard:} Users can view the top scores per category.
    \item \textbf{Replay Option:} Players can start a new round from the category selection screen.
\end{enumerate}

\subsection*{Admin Panel}
\begin{itemize}
    \item Admins log in using default credentials.
    \item The control panel allows adding and deleting questions.
    \item Questions are categorized and stored in a database.
    \item Admins can manage the entire question set dynamically.
\end{itemize}

\section*{Database Schema}
\subsection*{Users Table}
\begin{verbatim}
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);
\end{verbatim}

\subsection*{Questions Table}
\begin{verbatim}
CREATE TABLE Questions (
    question_id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    question_text TEXT NOT NULL,
    option_a TEXT NOT NULL,
    option_b TEXT NOT NULL,
    option_c TEXT NOT NULL,
    correct_option CHAR(1) NOT NULL
);
\end{verbatim}

\subsection*{Scores Table}
\begin{verbatim}
CREATE TABLE Scores (
    score_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category VARCHAR(50) NOT NULL,
    score INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);
\end{verbatim}

\section*{Installation Instructions}
\subsection*{Prerequisites}
\begin{itemize}
    \item Android Studio installed with Java SDK.
    \item PHP and MySQL (using XAMPP or WAMP).
    \item A web server to host the backend files.
\end{itemize}

\subsection*{Steps to Set Up}
\begin{enumerate}
    \item Clone the repository:
    \begin{verbatim}
    git clone https://github.com/karimitanii/QuizQuest.git
    \end{verbatim}
    \item Import the Android project into Android Studio.
    \item Set up a MySQL database using the provided schema.
    \item Place the PHP backend files in a web server directory (e.g., \texttt{htdocs} in XAMPP).
    \item Update API endpoints in the Android application as needed.
    \item Run the app in an emulator or a physical device.
\end{enumerate}

\section*{Contributing}
Contributions are welcome! Please follow these steps:
\begin{enumerate}
    \item Fork the repository.
    \item Create a new branch for your feature.
    \item Make your changes and commit them.
    \item Push the branch and submit a pull request.
\end{enumerate}

\section*{License}
This project is licensed under the MIT License.

\end{document}
