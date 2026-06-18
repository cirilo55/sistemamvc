# Development Rules

## General
- This is a build to learn project
- Do not use any externals Lib (composer or node)
- Follow Clean Code principles.
- Follow SOLID principles.
- Use Dependency Injection whenever possible.
- Write maintainable and readable code.
- Keep comments concise and useful.

## Business Context

- This is a financial SaaS for dropshipping stores.
- Focus on business rules; technical standards, architecture, coding patterns, and project structure are defined in this file.

## Core Business Rules

- One user can own multiple stores.
- Each store has its own products, inventory, sales, and metrics.
- Store data must be isolated.

## Products

- A product must contain cost price, sale price, inventory quantity, and active status.
- Inventory changes must always generate history records.

## Revenue

- Revenue is the sum of all approved sales.
- Cancelled orders must not count as revenue.
- Refunded orders must reduce revenue.

## Profit

- Profit = Revenue - Product Cost - Shipping Cost - Fees - Advertising Cost.
- Profit is the main metric of the system.

## Margin

- Margin = Profit / Revenue.
- Calculate margin per product and per store.

## ROAS

- ROAS = Revenue / Advertising Cost.
- Calculate ROAS per product and per store.

## Dashboard Priorities

- Always prioritize profit, margin, and ROAS, in that order.
- Never prioritize revenue alone.
- Products with negative profit or margin must be flagged.
- When creating new features, always answer: "How much profit is the store actually making?"

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