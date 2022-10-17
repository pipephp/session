<?php

use Pipe\Session;

use function Pipe\session;

beforeEach(function () {
    if (session_id()) {
        session_unset();
        session_destroy();
        session_write_close();
    }
});

it("can get session object from function", function () {
    expect(session())->toBeInstanceOf(Session::class);
});

it("can start session", function () {
    session()->start();
    expect($_SESSION)->toBeArray();
});

it("sets custom session name", function () {
    session()->name("differentname")->start();
    expect(session_name())->toEqual("differentname");
});

it("sets variable in session", function () {
    session()->start()->set("key", "val");
    expect($_SESSION["key"])->toEqual("val");
});

it("sets and gets var in session", function () {
    session()->start()->set("key", "val");
    expect(session()->get("key"))->toEqual("val");
});

it("deletes session var", function () {
    session()->start()->set("key", "val")->set("another", "one");
    expect(session()->delete("key")->get("key"))->toEqual(null);
    expect(session()->get("another"))->toEqual("one");
});

it("clears session", function () {
    expect(session()->start()->set("key", "val")->clear()->get("key"))->toEqual(null);
});
