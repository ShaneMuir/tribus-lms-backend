# Tribus LMS Backend

- Setup WordPress - Handle user management, storing challenges.
- Setup challenges CPT - Create metadata for challenge titles, description, difficulty.
- WP REST API - Endpoints to fetch challenges, saving submissions / evaluating user submissions

# Tribus LMS Frontend

- Setup VueJS - Fetch challenges, display them, allow user to write and test code.
- Live Coding Env - Integrate Monaco Editor
- Evaluation of Code - Write test cases to test submission code.
- Setup / Test Judge0 API - Handle remote execution of code
- Custom WP Plugin - Submit user code via a form to evaluate against test cases.

- Integrating Tests for User Submissions -
Test Cases in Backend: Store test cases (inputs and expected outputs) in your WordPress database as part of your challenge's post meta data. You can then evaluate the submitted code on your backend by running it through these tests.
Return Results to the Frontend: Once the code is executed on the server, return the results (pass/fail) and feedback (which tests passed or failed) to the frontend via the REST API or a custom endpoint.

- User Authentication and Progress Tracking -
Use WordPress's built-in user management to handle user authentication, allowing users to create accounts, track their progress, and view their submissions.
For tracking completed challenges, store the results in a custom database table or post meta that links users with their solved challenges and scores.

### Example Workflow
1. User visits a challenge page: The page fetches the challenge description and test cases from WordPress via the REST API.
2. User submits their code: The code is sent to the backend (either your own server or a code execution API like Judge0).
3. Run the code against tests: The server runs the code in a secure environment, evaluates it against predefined test cases, and returns the result (pass/fail).
4. Frontend displays the result: The user sees whether their solution passed all test cases, and feedback is given if it fails.
   
### Tools to Use
- Frontend: VueJS with Monaco Editor
- Backend: WordPress with custom post types and REST API endpoints.
- Code Execution: Judge0 API or Docker-based execution environment.
- Security: Use Docker or external APIs to ensure code runs in isolated environments.