# Linio Commit Standards

There are many ways to handle committing. Linio uses [Conventional Commits](https://www.conventionalcommits.org/) for its commit standards.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://www.ietf.org/rfc/rfc2119.txt).

## Commits

A commit SHOULD be a specific set of related changes. Commits MUST be able to be reverted without causing side effects.

### Subject

The subject contains a succinct description of the change:

- use the imperative, present tense: "change" not "changed" nor "changes"
- don't capitalize the first letter
- no dot (.) at the end

### Body

Just as in the **subject**, use the imperative, present tense: "change" not "changed" nor "changes".
The body should include the motivation for the change and contrast this with previous behavior.

### Footer

The footer should contain any information about **Breaking Changes** and is also the place to
reference Jira and GitHub issues that this commit **Closes**.

## Type Reference

Must be one of the following:

- **docs**: Documentation only changes
- **feat**: A new feature
- **fix**: A bug fix
- **perf**: A code change that improves performance
- **refactor**: A code change that neither fixes a bug nor adds a feature
- **style**: Changes that do not affect the meaning of the code (white-space, formatting, missing semi-colons, etc)
- **test**: Adding missing tests or correcting existing tests
- **revert**: Revert a commit

## Examples

- `docs: document developing locally in docker`
- `feat: add WebAuth support`
- `feat: add post about useful tips for developers`
- `refactor: cleanup exception handling`
- `fix: typo`
- `fix: missing null safety check`
- `fix: invalid catch block`
- `style: fixed`
- `style: add custom rule for line length`
