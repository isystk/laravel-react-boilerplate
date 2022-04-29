import React from "react";
import { BrowserRouter } from "react-router-dom";
import * as renderer from "react-test-renderer";
import { Logo } from "@/components/commons/Logo";

test("Logo", () => {
    const component = renderer.create(
        <BrowserRouter>
            <Logo />
        </BrowserRouter>
    );
    expect(component).toMatchSnapshot();
});
