import React from "react";
import LoginForm from "./Forms/LoginForm";
import RegisterForm from "./Forms/RegisterForm";
import EMailForm from "./Forms/EMailForm";
import ResetForm from "./Forms/ResetForm";
import Home from "./Forms/Home";
import Verify from "./Forms/Verify";

interface IProps {
  content: string;
}

const ContentSelector = (props: IProps) => {
  switch (props.content) {
    case "LoginForm":
      return <LoginForm />;
    case "RegisterForm":
      return <RegisterForm />;
    case "EMailForm":
      return <EMailForm />;
    case "ResetForm":
      return <ResetForm />;
    case "Home":
      return <Home />;
    case "Verify":
      return <Verify />;
    default:
      return <div>UnMatch</div>;
  }
};
export default ContentSelector;
