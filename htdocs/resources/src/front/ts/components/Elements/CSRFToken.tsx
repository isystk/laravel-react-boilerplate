import React from "react";
import { Form } from "react-bootstrap";
import PropTypes from "prop-types";
import { connect } from "react-redux";

interface IProps {
  csrf: string;
}

const CSRFToken = (props: IProps) => (
  <Form.Control type="hidden" name="_token" defaultValue={props.csrf} />
);

const mapStateToProps = (state) => {
  return {
    csrf: state.auth.csrf,
  };
};
const mapDispatchToProps = (dispatch) => ({});
export default connect(mapStateToProps, mapDispatchToProps)(CSRFToken);
