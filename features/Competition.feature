Feature: Competition
    In order to lose weight
    As a physically inappropriate person
    I need to create and use weight competitions

    @mink:sahi
    Scenario: Create a new competition
        Given there is no data in the system
        And I am on the homepage
        And I set the end date to 10 days from now
        And I press "Ready. Set. Go."
        Then I should be on "/view/1"
        And I should see "WeightRace presents"
        And I should see "You have 10 days left"

    Scenario: Create a new competition with racers
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
        And I should see "1st place"
        And I should see "80 kg currently"
        And I should see "70 kg goal"
        And I should see "10 kg left"
        And I should see "0 awards"
        And I should see "0 wobbly bits"

    Scenario: Create a competition with bad competition data
        Given there is no data in the system
        And I am on the homepage
        And I fill in "competition_name" with ""
        And I press "Ready. Set. Go"
        Then I should be on the homepage
        And I should see "Uh oh!"
        And I should see "Please ensure you have a name for your challenge."

    Scenario: Create a competition with bad racer data
        Given there is no data in the system
        And I am on the homepage
        And I fill in "racer1_name" with "Foo"
        And I press "Ready. Set. Go."
        Then I should be on "/view/1"
        And I should see "WeightRace presents"
        And I should see "Uh oh!"
        And I should see "Contestant 1 does not have a valid email address."
        And I should see "Contestant 1 should have a reasonable weight in kg."
        And I should see "Contestant 1 should have a reasonable height in cm."
        And I should see "Contestant 1 should have a reasonable goal weight in kg."

    Scenario: Create a competition and then add a racer
        Given there is no data in the system
        And I am on the homepage
        And I press "Ready. Set. Go."
        Then I should be on "/view/1"
        And I should see "WeightRace presents"
        And I should not see "1st place"
        When I fill in the following:
            | racer1_name        | Racer1              |
            | racer1_weight      | 80                  |
            | racer1_height      | 180                 |
            | racer1_goal_weight | 70                  |
            | racer1_email       | dion@thinkmoult.com |
        And I press "Add contestant"
        Then I should see "1st place"
        And I should see "80 kg currently"
        And I should see "70 kg goal"
        And I should see "10 kg left"
        And I should see "0 awards"
        And I should see "0 wobbly bits"

    Scenario: Create a competition with multiple racers
        Given there is no data in the system
        And I am on the homepage
        And I fill in the following:
            | racer1_name        | Racer1              |
            | racer1_weight      | 80                  |
            | racer1_height      | 180                 |
            | racer1_goal_weight | 70                  |
            | racer1_email       | dion@thinkmoult.com |
            | racer2_name        | Racer2              |
            | racer2_weight      | 80                  |
            | racer2_height      | 180                 |
            | racer2_goal_weight | 70                  |
            | racer2_email       | dion@thinkmoult.com |
        And I press "Ready. Set. Go."
        Then I should see "WeightRace presents"
        And I should see "Hello, Racer2"

    Scenario: Attempt to access a competition when not a participant
        Given there is no data in the system
        And I am on the homepage
        And I press "Ready. Set. Go."
        Then I should be on "/view/1"
        And I should see "WeightRace presents"
        When I fill in the following:
            | racer1_name        | Racer1              |
            | racer1_weight      | 80                  |
            | racer1_height      | 180                 |
            | racer1_goal_weight | 70                  |
            | racer1_email       | dion@thinkmoult.com |
        And I press "Add contestant"
        When I go to "/view/1"
        Then I should not see "WeightRace presents"
