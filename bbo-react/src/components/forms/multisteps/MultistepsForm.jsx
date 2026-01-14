import { useEffect, useRef, useState } from "react";
import "./style.css"; // garde ton CSS existant

export default function MultiStepForm() {
  const [currentStep, setCurrentStep] = useState(0);
  const [completed, setCompleted] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [selectedPlan, setSelectedPlan] = useState(null);

  const formRef = useRef(null);
  const stepsContainerRef = useRef(null);
  const progressRef = useRef(null);

  const steps = 5;
  const pricingPlans = [
  {
    id: "basic",
    name: "Basic",
    price: "5 000 FCFA",
    features: ["Accès standard", "Support email"],
  },
  {
    id: "pro",
    name: "Pro",
    price: "15 000 FCFA",
    features: ["Tout Basic", "Support prioritaire", "Statistiques"],
  },
  {
    id: "enterprise",
    name: "Enterprise",
    price: "Sur devis",
    features: ["Tout Pro", "Support dédié", "API"],
  },
];

  const stepsData = [
  {
    title: "Personal Information",
    content: (
      <>
        <input name="firstName" placeholder="First Name" required />
        <input name="lastName" placeholder="Last Name" required />
      </>
    ),
  },
  {
    title: "Choose a Plan",
    content: (
      <div className="pricing-grid">
        {pricingPlans.map((plan) => (
          <div
            key={plan.id}
            className={`pricing-card ${
              selectedPlan === plan.id ? "active" : ""
            }`}
            onClick={() => setSelectedPlan(plan.id)}
          >
            <h4>{plan.name}</h4>
            <p className="price">{plan.price}</p>
            <ul>
              {plan.features.map((f, i) => (
                <li key={i}>{f}</li>
              ))}
            </ul>
          </div>
        ))}
      </div>
    ),
    validate: () => selectedPlan !== null,
  },
  {
    title: "Contact Information",
    content: (
      <>
        <input type="email" name="email" placeholder="Email" required />
        <input type="tel" name="phone" placeholder="Phone" required />
      </>
    ),
  },
  {
    title: "Account Information",
    content: (
      <>
        <input name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="password" required />
        <input type="password" name="confirmPassword" placeholder="Confirm password" required />
      </>
    ),
  },
  {
    title: "About",
    content: (
      <textarea name="bio" rows="4" required />
    ),
  },
];


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
  
  useEffect(() => {
    document.documentElement.style.setProperty("--steps", steps);
    updateProgress();
  }, [currentStep]);

  const updateProgress = () => {
    const width = currentStep / (steps - 1);
    if (progressRef.current) {
      progressRef.current.style.transform = `scaleX(${width})`;
    }

    const stepEl = stepsContainerRef.current.children[currentStep];
    if (stepEl) {
      stepsContainerRef.current.style.height =
        stepEl.offsetHeight + "px";
    }
  };

  const isValidStep = () => {
    const stepEl = stepsContainerRef.current.children[currentStep];
    const fields = stepEl.querySelectorAll("input, textarea");
    return [...fields].every((field) => field.reportValidity());
  };

  const nextStep = () => {
    const step = stepsData[currentStep];

    if (step.validate && !step.validate()) {
        alert("Veuillez sélectionner une formule");
        return;
    }

    setCurrentStep((prev) => prev + 1);
  };


  const prevStep = () => {
    setCurrentStep((s) => Math.max(s - 1, 0));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!formRef.current.checkValidity()) return;

    const data = new FormData(formRef.current);
    console.log(Object.fromEntries(data));

    setSubmitting(true);

    setTimeout(() => {
      setCompleted(true);
      setSubmitting(false);
    }, 3000);
  };

  return (
    <form ref={formRef} className="form-wizard" onSubmit={handleSubmit}>
      {completed && (
        <div className="completed">
          <h3>Registration Successful!</h3>
          <p>Your account has been created.</p>
        </div>
      )}

      <h1>Registration</h1>

      {/* Progress */}
      <div className="progress-container">
        <div className="progress" ref={progressRef}></div>
        <ol>
          {["Personal", "Plan", "Contact", "Account", "About"].map((label, i) => (
            <li
              key={i}
              className={
                currentStep === i
                  ? "current"
                  : currentStep > i
                  ? "done"
                  : ""
              }
            >
              {label}
            </li>
          ))}
        </ol>
      </div>

      {/* Steps */}
      <div className="steps-container" ref={stepsContainerRef}>
        {stepsData.map((step, index) => (
            <div
            key={index}
            className={`step ${currentStep === index ? "current" : ""}`}
            style={{
                transform: `translateX(-${currentStep * 100}%)`,
            }}
            >
            <h3>{step.title}</h3>
            {step.content}
            </div>
        ))}
        </div>


      {/* Controls */}
      <div className="controls">
        {currentStep > 0 && (
          <button type="button" onClick={prevStep}>
            Prev
          </button>
        )}

        {currentStep < steps - 1 && (
          <button type="button" onClick={nextStep}>
            Next
          </button>
        )}

        {currentStep === steps - 1 && (
          <button type="submit" disabled={submitting}>
            {submitting ? "Submitting..." : "Submit"}
          </button>
        )}
      </div>
    </form>
  );
}
