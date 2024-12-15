# User Management API Documentation.
The provided code defines an API for user management in Laravel, specifically handling authentication and user data operations. Here’s a breakdown of the API endpoints and their functionality:

### 1. **Public Routes:**
These routes do not require authentication and allow users to register, log in, and log out.

- **POST `/register`**:
    - Registers a new user.
    - **Controller**: `AuthController@register`.

- **POST `/login`**:
    - Logs in an existing user and generates an access token.
    - **Controller**: `AuthController@login`.

- **POST `/logout`**:
    - Logs the user out by invalidating their current token.
    - **Controller**: `AuthController@logout`.
    - **Middleware**: `auth:api` — ensures the user must be authenticated.

### 2. **Authenticated Routes:**
These routes are protected by `auth:api` middleware, meaning the user must be authenticated with a valid token to access them.

- **GET `/user`**:
    - Returns the authenticated user’s information.
    - **Middleware**: `auth:api` — requires the user to be authenticated with an API token.

- **GET `/users`**:
    - Returns a list of all users.
    - **Controller**: `UserController@index`.

- **GET `/users/{id}`**:
    - Returns a specific user by ID.
    - **Controller**: `UserController@show`.

- **DELETE `/users/{id}`**:
    - Deletes a specific user by ID.
    - **Controller**: `UserController@delete`.

- **PUT `/users/{id}`**:
    - Updates a specific user's information.
    - **Controller**: `UserController@update`.

---

### Example Request/Response

#### **1. Register User**
- **URL**: `/register`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
      "name": "John Doe",
      "email": "john@example.com",
      "password": "password"
  }
  ```
- **Response**:
  ```json
  {
      "message": "User registered successfully",
      "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "email_verified_at": null,
          "created_at": "2024-12-15T00:00:00.000000Z",
          "updated_at": "2024-12-15T00:00:00.000000Z"
      }
  }
  ```

#### **2. Login User**
- **URL**: `/login`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
      "email": "john@example.com",
      "password": "password"
  }
  ```
- **Response**:
  ```json
  {
      "access_token": "your-jwt-token",
      "token_type": "bearer",
      "expires_in": 3600
  }
  ```

#### **3. Logout User**
- **URL**: `/logout`
- **Method**: `POST`
- **Request Header**: `Authorization: Bearer your-jwt-token`
- **Response**:
  ```json
  {
      "message": "Successfully logged out",
      "success": true
  }
  ```

#### **4. Get List of Users (Authenticated)**
- **URL**: `/users`
- **Method**: `GET`
- **Request Header**: `Authorization: Bearer your-jwt-token`
- **Response**:
  ```json
  [
      {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "created_at": "2024-12-15T00:00:00.000000Z",
          "updated_at": "2024-12-15T00:00:00.000000Z"
      },
      {
          "id": 2,
          "name": "Jane Doe",
          "email": "jane@example.com",
          "created_at": "2024-12-15T00:00:00.000000Z",
          "updated_at": "2024-12-15T00:00:00.000000Z"
      }
  ]
  ```

#### **5. Get Specific User**
- **URL**: `/users/{id}`
- **Method**: `GET`
- **Request Header**: `Authorization: Bearer your-jwt-token`
- **Response**:
  ```json
  {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "email_verified_at": null,
      "created_at": "2024-12-15T00:00:00.000000Z",
      "updated_at": "2024-12-15T00:00:00.000000Z"
  }
  ```

#### **6. Update User**
- **URL**: `/users/{id}`
- **Method**: `PUT`
- **Request Header**: `Authorization: Bearer your-jwt-token`
- **Request Body**:
  ```json
  {
      "name": "Updated Name",
      "email": "updated-email@example.com"
  }
  ```
- **Response**:
  ```json
  {
      "message": "User updated successfully",
      "user": {
          "id": 1,
          "name": "Updated Name",
          "email": "updated-email@example.com",
          "created_at": "2024-12-15T00:00:00.000000Z",
          "updated_at": "2024-12-15T00:00:00.000000Z"
      }
  }
  ```

#### **7. Delete User**
- **URL**: `/users/{id}`
- **Method**: `DELETE`
- **Request Header**: `Authorization: Bearer your-jwt-token`
- **Response**:
  ```json
  {
      "message": "User deleted successfully",
      "success": true
  }
  ```

---

### Notes:
- The `auth:api` middleware ensures that all routes except for `register`, `login`, and `logout` are accessible only to authenticated users.
- The JWT access token is required for the routes that are protected by `auth:api`.
- The `UserController` should handle the logic for the `index`, `show`, `delete`, and `update` actions.

This API provides basic user management functionality, including registration, authentication, and CRUD operations for users.
