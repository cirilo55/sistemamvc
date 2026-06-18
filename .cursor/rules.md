# Development Rules

## General

- Follow Clean Code principles.
- Follow SOLID principles.
- Use Dependency Injection whenever possible.
- Write maintainable and readable code.
- Keep comments concise and useful.

## Scope Control

- Do not create Dockerfiles.
- Do not create CI/CD pipelines.
- Do not configure cloud infrastructure.
- Do not configure Kubernetes.
- Do not create deployment scripts.
- Do not modify files outside the requested scope.

## Backend

- Prefer Laravel for PHP projects.
- Use Repository Pattern when appropriate.
- Prefer service classes for business logic.
- Keep controllers thin.

## Frontend

- Prefer React when frontend examples are needed.
- Use TypeScript when possible.
- Avoid unnecessary dependencies.

## Output

- Return only the files requested.
- Explain architectural decisions briefly.
- Do not generate documentation unless requested.
- Do not generate tests unless requested.

## Token Optimization

- Read only files directly related to the task.
- Do not analyze the entire project.
- Ask before scanning more than 5 files.
- Prefer modifying existing code over generating new structures.
- Keep explanations under 100 words unless requested.