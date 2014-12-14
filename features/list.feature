Feature: List issues
    In order to browse the issues
    As an issue tracker user
    I need to see a list of the created issues

    Scenario: There are multiple issues
        Given there are issues:
          | title                           | description                            | author  |
          | Exploding cyclomatic complexity | The package foo have too complex code. | @foobar |
          | FooTest::testBar tests nothing  | PHPUnit reports it as a risky test.    | @foobar |
        When I go to "/"
        Then I should see 2 issues

    Scenario: There is one issue
        Given there are issues:
          | title                           | description                            | author  |
          | Exploding cyclomatic complexity | The package foo have too complex code. | @foobar |
        When I go to "/"
        Then I should see 1 issue

    Scenario: There are no issues
        Given there are no issues
        When I go to "/"
        Then I should see 0 issues
