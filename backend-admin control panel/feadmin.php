<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
  header("Location: loginadmin.html"); // Redirect to login page
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quiz Quest - Admin Portal</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      margin: 0;
      padding: 20px;
    }

    h1 {
      text-align: center;
    }

    .controls {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .btn {
      padding: 10px 15px;
      font-size: 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .btn-red {
      background-color: #f44336;
      color: white;
    }

    .btn-red:hover {
      background-color: #d32f2f;
    }

    .btn-green {
      background-color: #4caf50;
      color: white;
    }

    .btn-green:hover {
      background-color: #388e3c;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table th,
    table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }

    table th {
      background-color: #f4f4f4;
    }

    .form-container {
      margin-top: 20px;
      padding: 20px;
      background: white;
      border-radius: 8px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
  </style>
</head>

<body>
  <h1>Quiz Quest - Admin Portal</h1>
  <div class="controls">
    <select id="categorySelect" class="form-group">
      <option value="">Select Category</option>
      <option value="Science">Science</option>
      <option value="History">History</option>
      <option value="Football">Football</option>
      <option value="Geography">Geography</option>
      <option value="Technology">Technology</option>
      <option value="Entertainment">Entertainment</option>
    </select>
    <button class="btn btn-green" id="addQuestionBtn">Add Question</button>
  </div>

  <div id="questionsTable">
    <!-- Questions will be dynamically loaded here -->
  </div>

  <div id="addQuestionForm" style="display: none;" class="form-container">
    <h2>Add Question</h2>
    <div class="form-group">
      <label for="category">Category</label>
      <select id="category" required>
        <option value="Science">Science</option>
        <option value="History">History</option>
        <option value="Football">Football</option>
        <option value="Geography">Geography</option>
        <option value="Technology">Technology</option>
        <option value="Entertainment">Entertainment</option>
      </select>
    </div>
    <div class="form-group">
      <label for="questionText">Question Text</label>
      <input type="text" id="questionText" placeholder="Enter question text" required />
    </div>
    <div class="form-group">
      <label for="optionA">Option A</label>
      <input type="text" id="optionA" placeholder="Enter option A" required />
    </div>
    <div class="form-group">
      <label for="optionB">Option B</label>
      <input type="text" id="optionB" placeholder="Enter option B" required />
    </div>
    <div class="form-group">
      <label for="optionC">Option C</label>
      <input type="text" id="optionC" placeholder="Enter option C" required />
    </div>
    <div class="form-group">
      <label for="correctOption">Correct Option</label>
      <select id="correctOption" required>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
      </select>
    </div>
    <button class="btn btn-green" id="saveQuestionBtn">Save Question</button>
  </div>

  <script>
    const addQuestionBtn = document.getElementById("addQuestionBtn");
    const addQuestionForm = document.getElementById("addQuestionForm");
    const questionsTable = document.getElementById("questionsTable");
    const categorySelect = document.getElementById("categorySelect");

    function fetchQuestions(category) {
      if (!category) return;
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "admin_questions.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onload = function() {
        if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            let tableHtml = `
                            <table>
                                <tr>
                                    <th>ID</th>
                                    <th>Category</th>
                                    <th>Question</th>
                                    <th>Option A</th>
                                    <th>Option B</th>
                                    <th>Option C</th>
                                    <th>Correct Option</th>
                                    <th>Actions</th>
                                </tr>`;
            response.questions.forEach((question) => {
              tableHtml += `
                                <tr>
                                    <td>${question.question_id}</td>
                                    <td>${question.category}</td>
                                    <td>${question.question_text}</td>
                                    <td>${question.option_a}</td>
                                    <td>${question.option_b}</td>
                                    <td>${question.option_c}</td>
                                    <td>${question.correct_option}</td>
                                    <td>
                                        <button class="btn btn-red" onclick="deleteQuestion(${question.question_id})">Delete</button>
                                    </td>
                                </tr>`;
            });
            tableHtml += `</table>`;
            questionsTable.innerHTML = tableHtml;
          } else {
            questionsTable.innerHTML = `<p>No questions found for the selected category.</p>`;
          }
        }
      };
      xhr.send(`action=fetch&category=${encodeURIComponent(category)}`);
    }

    function deleteQuestion(questionId) {
      if (confirm(`Are you sure you want to delete question ID = ${questionId}?`)) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "admin_questions.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
          if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
              alert(`Question ID = ${questionId} has been deleted.`);
              fetchQuestions(categorySelect.value);
            }
          }
        };
        xhr.send(`action=delete&question_id=${questionId}`);
      }
    }

    document.getElementById("saveQuestionBtn").addEventListener("click", function() {
      const category = document.getElementById("category").value;
      const questionText = document.getElementById("questionText").value;
      const optionA = document.getElementById("optionA").value;
      const optionB = document.getElementById("optionB").value;
      const optionC = document.getElementById("optionC").value;
      const correctOption = document.getElementById("correctOption").value;

      if (!category || !questionText || !optionA || !optionB || !optionC || !correctOption) {
        alert("Please fill out all fields.");
        return;
      }

      const xhr = new XMLHttpRequest();
      xhr.open("POST", "admin_questions.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onload = function() {
        if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            alert("Question added successfully.");
            addQuestionForm.style.display = "none";
            fetchQuestions(categorySelect.value);
          } else {
            alert(`Error: ${response.message}`);
          }
        }
      };
      xhr.send(`action=add&category=${encodeURIComponent(category)}&question_text=${encodeURIComponent(questionText)}&option_a=${encodeURIComponent(optionA)}&option_b=${encodeURIComponent(optionB)}&option_c=${encodeURIComponent(optionC)}&correct_option=${encodeURIComponent(correctOption)}`);
    });

    addQuestionBtn.addEventListener("click", function() {
      addQuestionForm.style.display = "block";
      questionsTable.innerHTML = ""; // Hide the questions table
    });

    categorySelect.addEventListener("change", function() {
      fetchQuestions(this.value);
    });
  </script>
</body>

</html>