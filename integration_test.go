package hnotes_test

import (
	"fmt"
	"io/ioutil"
	"net/http"
	"strings"

	"github.com/cucumber/godog"
)

const (
	URL = "http://localhost:8080"
)

var (
	lastResponse *http.Response
)

func iAmNotAuthenticated() error {
	return nil
}

func iShouldSee(text string) error {
	res, err := ioutil.ReadAll(lastResponse.Body)
	if err != nil {
		return err
	}
	body := string(res)

	if !strings.Contains(body, text) {
		return fmt.Errorf("Did not find '%s' in Body:\n%s", text, body)
	}

	return nil
}

func iVisit(path string) error {
	res, err := http.Get(URL + path)
	if err != nil {
		return err
	}

	lastResponse = res

	return nil
}

func InitializeScenario(ctx *godog.ScenarioContext) {
	ctx.Step(`^I am not authenticated$`, iAmNotAuthenticated)
	ctx.Step(`^I should see "([^"]*)"$`, iShouldSee)
	ctx.Step(`^I visit "([^"]*)"$`, iVisit)
}
