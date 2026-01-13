import "./App.css";
import { useState } from "react";

export default function App() {
  const [values, setValues] = useState({
    category: "",
    formule: "",
    name: "",
    email: "",
    parentName: "",
    age: "",
    niveau: "",
    activite: "",
    site: ""
  });
  const [next, setNext] = useState(false);

  const handleInputChange = (event) => {
    event.preventDefault();

    const { name, value } = event.target;
    setValues((values) => ({
      ...values,
      [name]: value
    }));
  };

  const [submitted, setSubmitted] = useState(false);
  const [valid, setValid] = useState(false);
  const [step, setStep] = useState(1);

  const handleSubmit = (e) => {
    e.preventDefault();
    if (values.firstName && values.lastName && values.email) {
      setValid(true);
    }
    setSubmitted(true);
  };

  return (
    <div className="form-container">
      <form className="register-form" onSubmit={handleSubmit}>
        {submitted && valid && (
          <div className="success-message">
            <h3>
              {" "}
              Welcome {values.firstName} {values.lastName}{" "}
            </h3>
            <div> Your registration was successful! </div>
          </div>
        )}
          {!valid && step === 1 &&(
            (
              <><select
                class="form-field"
                name="formule"
                onChange={handleInputChange}
              >
                <option value="enfant" >Enfant</option>
                <option value="parent" >Parent</option>
              </select>
              <button class="form-field" onClick={() => setStep(2)} >
                Suivant
              </button></>
            )
          )}
          {submitted && !values.formule && (
            <span id="first-name-error">Choisissez votre formule </span>
          )}
          {!valid && step === 2 &&(
              <>
              <input
                type="text"
                class="form-field"
                value={values.name}
                name="name"
                onChange={handleInputChange}
              />
              <button class="form-field" onClick={() => setStep(2)} >
                Suivant
              </button></>
          )}
          {submitted && !values.firstName && (
            <span id="first-name-error">Please enter a first name</span>
          )}
      </form>
    </div>
  );
}
