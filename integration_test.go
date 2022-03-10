package hnotes_test

import (
	"errors"
	"fmt"
	"io/ioutil"
	"net"
	"net/http"
	"os/exec"
	"regexp"
	"strings"
	"syscall"
	"time"

	"github.com/cucumber/godog"
)

const (
	URL = "http://localhost:8080"
)

type response struct {
	code int
	body string
}

var (
	lastResponse *response
	cmd          *exec.Cmd
)

/// Background
func theAppIsStarted() error {
	cmd = exec.Command("cargo", "run")
	cmd.SysProcAttr = &syscall.SysProcAttr{Pdeathsig: syscall.SIGTERM}
	if err := cmd.Start(); err != nil {
		return err
	}

	// wait for app to listen to port
	i := 10
	for i > 0 {
		if _, err := net.DialTimeout("tcp", "localhost:8080", time.Second); err == nil {
			return nil
		}

		i--
		time.Sleep(time.Second)
	}

	return errors.New("App not started up within 10 seconds.")
}

/// Given
func iAmNotAuthenticated() error {
	return nil
}

/// When
func iVisit(path string) error {
	// Fetch URL
	res, err := http.Get(URL + path)
	if err != nil {
		return err
	}

	// Read body
	b, err := ioutil.ReadAll(res.Body)
	if err != nil {
		return err
	}
	body := string(b)

	// Save result
	lastResponse = &response{
		code: res.StatusCode,
		body: body,
	}

	return nil
}

/// Then
func thatRequestShouldBeSuccessful() error {
	code := lastResponse.code
	if code < 200 || code >= 300 {
		return fmt.Errorf("HTTP code was '%d'", code)
	}
	return nil
}

func theTitleShouldBe(text string) error {
	titleRe := regexp.MustCompile("<title>([^<]+)</title>")
	matches := titleRe.FindStringSubmatch(lastResponse.body)

	if matches == nil {
		return fmt.Errorf("No title found in body:\n%s", lastResponse.body)
	}

	title := matches[1]

	if title != text {
		return fmt.Errorf("Title '%s' does not match", title)
	}

	return nil
}

func theBodyShouldContain(text string) error {
	if !strings.Contains(lastResponse.body, text) {
		return fmt.Errorf("Did not find '%s' in Body:\n%s", text, lastResponse.body)
	}

	return nil
}
func InitializeScenario(ctx *godog.ScenarioContext) {
	// Sort alphabetically
	ctx.Step(`^I am not authenticated$`, iAmNotAuthenticated)
	ctx.Step(`^I visit "([^"]*)"$`, iVisit)
	ctx.Step(`^that request should be successful$`, thatRequestShouldBeSuccessful)
	ctx.Step(`^the app is started$`, theAppIsStarted)
	ctx.Step(`^the body should contain "([^"]*)"$`, theBodyShouldContain)
	ctx.Step(`^the title should be "([^"]*)"$`, theTitleShouldBe)
}
