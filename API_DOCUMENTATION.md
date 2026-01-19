# Quiz System API Documentation

## Base URL
```
http://localhost:8000/api/v1
```

## General Response Format

### Success Response
```json
{
    "success": true,
    "data": [...],
    "message": "Operation completed successfully."
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error description"
}
```

---

## Categories API

### Get All Categories
**GET** `/categories`

Retrieves all quiz categories.

**Response Example:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Mathematics",
            "created_at": "2025-01-19T10:00:00.000000Z",
            "updated_at": "2025-01-19T10:00:00.000000Z"
        },
        {
            "id": 2,
            "name": "Science",
            "created_at": "2025-01-19T10:00:00.000000Z",
            "updated_at": "2025-01-19T10:00:00.000000Z"
        }
    ],
    "message": "Categories retrieved successfully."
}
```

### Get Single Category
**GET** `/categories/{id}`

Retrieves a specific category by ID.

**Parameters:**
- `id` (integer, required): Category ID

**Response Example:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Mathematics",
        "created_at": "2025-01-19T10:00:00.000000Z",
        "updated_at": "2025-01-19T10:00:00.000000Z"
    },
    "message": "Category retrieved successfully."
}
```

### Get Category Quizzes
**GET** `/categories/{category}/quizzes`

Retrieves all quizzes belonging to a specific category.

**Parameters:**
- `category` (integer, required): Category ID

**Response Example:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Basic Algebra",
            "time_limit": 30,
            "category_id": 1,
            "is_active": true,
            "created_at": "2025-01-19T10:00:00.000000Z",
            "updated_at": "2025-01-19T10:00:00.000000Z"
        }
    ],
    "message": "Quizzes retrieved successfully."
}
```

---

## Quizzes API

### Get All Quizzes
**GET** `/quizzes`

Retrieves all quizzes with their categories.

**Response Example:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Basic Algebra",
            "time_limit": 30,
            "category_id": 1,
            "is_active": true,
            "created_at": "2025-01-19T10:00:00.000000Z",
            "updated_at": "2025-01-19T10:00:00.000000Z",
            "category": {
                "id": 1,
                "name": "Mathematics"
            }
        }
    ],
    "message": "Quizzes retrieved successfully."
}
```

### Get Single Quiz
**GET** `/quizzes/{id}`

Retrieves a specific quiz with its questions and category.

**Parameters:**
- `id` (integer, required): Quiz ID

**Response Example:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Basic Algebra",
        "time_limit": 30,
        "category_id": 1,
        "is_active": true,
        "created_at": "2025-01-19T10:00:00.000000Z",
        "updated_at": "2025-01-19T10:00:00.000000Z",
        "category": {
            "id": 1,
            "name": "Mathematics"
        },
        "questions": [
            {
                "id": 1,
                "quiz_id": 1,
                "question_text": "What is 2 + 2?",
                "options": ["3", "4", "5", "6"],
                "correct_answer": 1,
                "created_at": "2025-01-19T10:00:00.000000Z",
                "updated_at": "2025-01-19T10:00:00.000000Z"
            }
        ]
    },
    "message": "Quiz retrieved successfully."
}
```

### Get Quiz Questions
**GET** `/quizzes/{quiz}/questions`

Retrieves all questions for a specific quiz.

**Parameters:**
- `quiz` (integer, required): Quiz ID

**Response Example:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "quiz_id": 1,
            "question_text": "What is 2 + 2?",
            "options": ["3", "4", "5", "6"],
            "correct_answer": 1,
            "created_at": "2025-01-19T10:00:00.000000Z",
            "updated_at": "2025-01-19T10:00:00.000000Z"
        }
    ],
    "message": "Questions retrieved successfully."
}
```

---

## Questions API

### Get All Questions
**GET** `/questions`

Retrieves all questions with their associated quizzes.

**Response Example:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "quiz_id": 1,
            "question_text": "What is 2 + 2?",
            "options": ["3", "4", "5", "6"],
            "correct_answer": 1,
            "created_at": "2025-01-19T10:00:00.000000Z",
            "updated_at": "2025-01-19T10:00:00.000000Z",
            "quiz": {
                "id": 1,
                "title": "Basic Algebra"
            }
        }
    ],
    "message": "Questions retrieved successfully."
}
```

### Get Single Question
**GET** `/questions/{id}`

Retrieves a specific question with its quiz information.

**Parameters:**
- `id` (integer, required): Question ID

**Response Example:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "quiz_id": 1,
        "question_text": "What is 2 + 2?",
        "options": ["3", "4", "5", "6"],
        "correct_answer": 1,
        "created_at": "2025-01-19T10:00:00.000000Z",
        "updated_at": "2025-01-19T10:00:00.000000Z",
        "quiz": {
            "id": 1,
            "title": "Basic Algebra"
        }
    },
    "message": "Question retrieved successfully."
}
```

---

## Data Models

### Category
```json
{
    "id": "integer",
    "name": "string",
    "is_active": "boolean",
    "description": "text (nullable)",
    "icon": "string (nullable)",
    "order": "integer",
    "created_at": "datetime",
    "updated_at": "datetime"
}
```

### Quiz
```json
{
    "id": "integer",
    "title": "string",
    "description": "text (nullable)",
    "time_limit": "integer (minutes)",
    "difficulty": "string (easy|medium|hard)",
    "total_points": "integer",
    "image": "string (nullable)",
    "category_id": "integer",
    "is_active": "boolean",
    "attempts_count": "integer",
    "rating": "decimal (3,2)",
    "created_at": "datetime",
    "updated_at": "datetime",
    "category": "Category object (when included)",
    "questions": "Question array (when included)"
}
```

### Question
```json
{
    "id": "integer",
    "quiz_id": "integer",
    "question_text": "string",
    "options": "array (string[])",
    "correct_answer": "integer (index of correct option)",
    "created_at": "datetime",
    "updated_at": "datetime",
    "quiz": "Quiz object (when included)"
}
```

### Result
```json
{
    "id": "integer",
    "device_id": "string",
    "quiz_id": "integer",
    "score": "integer",
    "total_questions": "integer",
    "completed_at": "datetime",
    "created_at": "datetime",
    "updated_at": "datetime",
    "quiz": "Quiz object (when included)"
}
```

---

## Results API

### Submit Quiz Answers
**POST** `/results/submit`

Submit quiz answers and store the result for a device.

**Request Body:**
```json
{
    "device_id": "unique_device_identifier",
    "quiz_id": 1,
    "answers": [
        {
            "question_id": 1,
            "selected_answer": 1
        },
        {
            "question_id": 2,
            "selected_answer": 0
        }
    ]
}
```

**Response Example:**
```json
{
    "success": true,
    "data": {
        "result_id": 1,
        "quiz_id": 1,
        "score": 1,
        "total_questions": 2,
        "percentage": 50.0,
        "completed_at": "2025-01-19T10:30:00.000000Z"
    },
    "message": "Quiz submitted successfully!"
}
```

### Get Completed Quizzes for Device
**GET** `/results/device/{device_id}/completed`

Retrieve all completed quizzes for a specific device.

**Parameters:**
- `device_id` (string, required): Unique device identifier

**Response Example:**
```json
{
    "success": true,
    "data": [
        {
            "result_id": 1,
            "quiz": {
                "id": 1,
                "title": "Basic Algebra",
                "category": {
                    "id": 1,
                    "name": "Mathematics"
                }
            },
            "score": 8,
            "total_questions": 10,
            "percentage": 80.0,
            "completed_at": "2025-01-19T10:30:00.000000Z"
        }
    ],
    "message": "Completed quizzes retrieved successfully."
}
```

### Get Device Statistics
**GET** `/results/device/{device_id}/statistics`

Get quiz performance statistics for a specific device.

**Parameters:**
- `device_id` (string, required): Unique device identifier

**Response Example:**
```json
{
    "success": true,
    "data": {
        "total_quizzes_taken": 5,
        "overall_average_score": 75.5,
        "total_questions_answered": 50,
        "correct_answers": 38,
        "category_performance": {
            "Mathematics": {
                "quizzes_taken": 3,
                "average_percentage": 80.0
            },
            "Science": {
                "quizzes_taken": 2,
                "average_percentage": 68.5
            }
        }
    },
    "message": "Statistics retrieved successfully."
}
```

### Get Result Details
**GET** `/results/{id}`

Retrieve detailed information about a specific quiz result.

**Parameters:**
- `id` (integer, required): Result ID

**Response Example:**
```json
{
    "success": true,
    "data": {
        "result_id": 1,
        "quiz": {
            "id": 1,
            "title": "Basic Algebra",
            "time_limit": 30,
            "category": {
                "id": 1,
                "name": "Mathematics"
            }
        },
        "score": 8,
        "total_questions": 10,
        "percentage": 80.0,
        "completed_at": "2025-01-19T10:30:00.000000Z"
    },
    "message": "Result retrieved successfully."
}
```

---

## Error Codes

- **200**: Success
- **404**: Resource Not Found
- **422**: Validation Error
- **500**: Internal Server Error

---

## Usage Examples

### Using curl
```bash
# Get all categories
curl http://localhost:8000/api/v1/categories

# Get a specific quiz
curl http://localhost:8000/api/v1/quizzes/1

# Get quizzes in a category
curl http://localhost:8000/api/v1/categories/1/quizzes

# Submit quiz answers
curl -X POST http://localhost:8000/api/v1/results/submit \
  -H "Content-Type: application/json" \
  -d '{
    "device_id": "android_device_123",
    "quiz_id": 1,
    "answers": [
      {"question_id": 1, "selected_answer": 1},
      {"question_id": 2, "selected_answer": 0}
    ]
  }'

# Get completed quizzes for a device
curl http://localhost:8000/api/v1/results/device/android_device_123/completed

# Get device statistics
curl http://localhost:8000/api/v1/results/device/android_device_123/statistics
```

### Using JavaScript (fetch)
```javascript
// Get all categories
fetch('http://localhost:8000/api/v1/categories')
    .then(response => response.json())
    .then(data => console.log(data));

// Get quiz with questions
fetch('http://localhost:8000/api/v1/quizzes/1')
    .then(response => response.json())
    .then(data => console.log(data));

// Submit quiz answers
fetch('http://localhost:8000/api/v1/results/submit', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        device_id: 'android_device_123',
        quiz_id: 1,
        answers: [
            {question_id: 1, selected_answer: 1},
            {question_id: 2, selected_answer: 0}
        ]
    })
})
.then(response => response.json())
.then(data => console.log(data));

// Get completed quizzes for device
fetch('http://localhost:8000/api/v1/results/device/android_device_123/completed')
    .then(response => response.json())
    .then(data => console.log(data));

// Get device statistics
fetch('http://localhost:8000/api/v1/results/device/android_device_123/statistics')
    .then(response => response.json())
    .then(data => console.log(data));
```

### Using Postman
1. Set request type to GET
2. Enter URL: `http://localhost:8000/api/v1/categories`
3. Click Send

---

## Notes

- All endpoints are read-only (GET requests only) except for result submission
- The API uses JSON format for all responses
- All timestamps are in UTC format
- The `correct_answer` field in questions represents the index of the correct option in the `options` array (0-based)
- Quiz time limits are specified in minutes
- **Results are tracked using device_id only - no user authentication required**
- **device_id must be a unique identifier for each device (e.g., Android device ID, UUID)**
- **Only active categories and quizzes are returned by default**
- **Categories are ordered by 'order' field, then by name**
- **Quizzes are ordered by creation date (newest first)**
