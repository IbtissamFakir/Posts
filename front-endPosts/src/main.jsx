import { createRoot } from "react-dom/client";
import "./index.css";
import NavBar from "./components/NavBar";
import { BrowserRouter } from "react-router-dom";
import App from "./components/App";
import { Toaster } from "react-hot-toast";
createRoot(document.getElementById("root")).render(
  <BrowserRouter>
    <NavBar />
     <Toaster position="top-center" />
    <App/>
  </BrowserRouter>,
);
