Feature: Update
    In order to compete in a race
    As somebody who has lost some weight
    I need to update my weight status

    Scenario: Update weight on a fresh competition
        Given there is no data in the system
        And I am on the homepage
        And I fill in the following:
            | racer1_name        | Racer1              |
            | racer1_weight      | 80                  |
            | racer1_height      | 180                 |
            | racer1_goal_weight | 70                  |
            | racer1_email       | dion@thinkmoult.com |
        And I press "Ready. Set. Go."
        Then I should see "WeightRace presents"
        And I should see "Let's update your weight today, Racer1."
        When I fill in "update_weight" with "80"
        And I press "Update my weight"
        Then I should see "Awesome. You've updated your weight today, Racer1."
