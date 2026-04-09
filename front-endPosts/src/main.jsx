import { createRoot } from "react-dom/client";
import "./index.css";
import { BrowserRouter } from "react-router-dom";
import NavBar from "./components/NavBar";
createRoot(document.getElementById("root")).render(
  <BrowserRouter>
    <NavBar />
  </BrowserRouter>,
);
