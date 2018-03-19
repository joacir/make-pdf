# How to contribute

Your contributions will be welcome! There are several ways to help out:

* Create an [issue](https://github.com/aelian/make-pdf/issues) on GitHub, if you have found a bug
* Write test cases for open bug issues
* Write patches for open bug/feature issues, preferably with test cases included
* Contribute to the [documentation](https://github.com/aelian/make-pdf/README.md)

There are a few guidelines that we need contributors to follow so that we have a
chance of keeping on top of things.

## Code of Conduct

As contributors and maintainers of the Make PDF project, we pledge to respect everyone who contributes by posting issues, updating documentation, submitting pull requests, providing feedback in comments, and any other activities.

Communication through any of Make PDF's channels (GitHub, mailing lists, Twitter, etc.) must be constructive and never resort to personal attacks, trolling, public or private harrassment, insults, or other unprofessional conduct.

We promise to extend courtesy and respect to everyone involved in this project regardless of gender, gender identity, sexual orientation, disability, age, race, ethnicity, religion, or level of experience. We expect anyone contributing to the Make PDF project to do the same.

If any member of the community violates this code of conduct, the maintainers of the Make PDF project may take action, removing issues, comments, and PRs or blocking accounts as deemed appropriate.

If you are subject to or witness unacceptable behavior, or have any other concerns, please email us at ti@aelian.com.br.

## Getting Started

* Make sure you have a [GitHub account](https://github.com/signup/free).
* Submit an [issue](https://github.com/cakephp/cakephp/issues), assuming one does not already exist.
  * Clearly describe the issue including steps to reproduce when it is a bug.
  * Make sure you fill in the earliest version that you know has the issue.
* Fork the repository on GitHub.

## Making Changes

* Create a topic branch from where you want to base your work.
  * This is usually the master branch.
  * Only target release branches if you are certain your fix must be on that
    branch.
  * To quickly create a topic branch based on master; `git branch
    master/my_contribution master` then checkout the new branch with `git
    checkout master/my_contribution`. Better avoid working directly on the
    `master` branch, to avoid conflicts if you pull in updates from origin.
* Make commits of logical units.
* Check for unnecessary whitespace with `git diff --check` before committing.
* Use descriptive commit messages and reference the #issue number.
* Core test cases should continue to pass. 
* Your work should apply the [PSR-2 coding style guide](https://www.php-fig.org/psr/psr-2/).

## Which branch to base the work

* Bugfix branches will be based on master.
* New features that are backwards compatible will be based on the appropriate 'next' branch. For example if you want to contribute to the next 1.x branch, you should base your changes on `1.next`.
* New features or other non backwards compatible changes will go in the next major release branch. 

## Submitting Changes

* Push your changes to a topic branch in your fork of the repository.
* Submit a pull request to the repository in the Aelian organization, with the
  correct target branch.

