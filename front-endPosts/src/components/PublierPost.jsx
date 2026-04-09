import React, { useRef, useState } from "react";
import { LogOut, Image as ImageIcon, Paperclip, Send } from "lucide-react";

function PublierPost() {
  const [champs, setChamps] = useState({
    titre: "",
    content: "",
    image: [],
    files: [],
  });

  const imageInputRef = useRef(null);
  const fileInputRef = useRef(null);

  // Gérer les zones de texte
  function handleChange(e) {
    const { name, value } = e.target;
    setChamps((prev) => ({ ...prev, [name]: value }));
  }

  // l'ajout de fichiers/images (Multiples)
  function handleFileChange(e) {
    const { name, files } = e.target;
    setChamps((prev) => ({
      ...prev,
      [name]: [...prev[name], ...Array.from(files)],
    }));
  }

  // Supprimer un fichier sélectionné avant l'envoi (Optionnel mais recommandé)
  function removeFile(type, index) {
    setChamps((prev) => ({
      ...prev,
      [type]: prev[type].filter((_, i) => i !== index),
    }));
  }

  async function handleSubmit(e) {
    e.preventDefault();

    // On laisse ça commenté pour le moment (en attendant la page Login)
    // const token = localStorage.getItem("token");

    const formData = new FormData();
    formData.append("titre", champs.titre);
    formData.append("content", champs.content);

    // --- AJOUT TEMPORAIRE POUR LE TEST DU GROUPE ---
    // On met l'ID 1 (ou un ID qui existe
    //  dans votre base de données)
    formData.append("user_id", 1);

    champs.image.forEach((img) => formData.append("images[]", img));
    champs.files.forEach((file) => formData.append("attachments[]", file));

    try {
      const response = await fetch("http://127.0.0.1:8000/api/posts", {
        method: "POST",
        // On commente le headers car le Token n'est pas encore prêt
        // headers: {
        //   Authorization: `Bearer ${token}`,
        //   Accept: "application/json",
        // },
        headers: { Accept: "application/json" },

        body: formData,
      });

      if (response.ok) {
        alert("Publication réussie !");
        setChamps({ titre: "", content: "", image: [], files: [] });
      } else {
        const errorData = await response.json();
        console.error("Détails de l'erreur :", errorData);
        alert(
          "Erreur : " +
            (errorData.message ||
              "Vérifiez que l'utilisateur ID 1 existe en BDD"),
        );
      }
    } catch (error) {
      alert("Erreur lors de l'envoi");
    }
  }

  return (
    <div className="flex p-6 gap-6 bg-gray-50 min-h-screen font-sans">
      {/* Sidebar Profil */}
      <aside className="bg-white h-fit text-gray-800 w-80 rounded-2xl p-6 flex flex-col shadow-lg border border-gray-200">
        <div className="flex flex-col items-center gap-4">
          <div className="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-2xl font-bold shadow-inner">
            YA
          </div>
          <div className="text-center">
            <h3 className="font-semibold text-lg">Yassine Amrani</h3>
            <p className="text-blue-500 text-sm font-medium">Stagiaire</p>
            <p className="text-gray-400 text-xs mt-1">
              Développement Digital • DEVOWF201
            </p>
          </div>
          <hr className="w-full border-gray-100 my-4" />
        </div>
        <button className="flex items-center gap-3 text-gray-500 hover:text-red-500 transition-colors mt-4">
          <LogOut size={18} />
          <span className="text-sm font-medium">Déconnexion</span>
        </button>
      </aside>

      {/*Zones de texte */}
      <div className="flex-1 flex justify-start items-start">
        <form
          onSubmit={handleSubmit}
          className="bg-white w-full max-w-2xl rounded-2xl shadow-md p-6 flex flex-col gap-5 border border-gray-200"
        >
          <h2 className="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent">
            Nouvelle publication
          </h2>

          <input
            type="text"
            placeholder="Titre de votre post..."
            onChange={handleChange}
            name="titre"
            value={champs.titre}
            required
            className="w-full p-3 rounded-xl border border-gray-100 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-400 transition-all"
          />

          <textarea
            placeholder="Partagez vos idées ici..."
            onChange={handleChange}
            name="content"
            value={champs.content}
            required
            className="w-full p-3 h-44 rounded-xl border border-gray-100 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-400 resize-none transition-all"
          ></textarea>

          {/* Affichage des fichiers sélectionnés */}
          {(champs.image.length > 0 || champs.files.length > 0) && (
            <div className="flex flex-wrap gap-2 p-3 bg-purple-50 rounded-xl">
              {champs.image.map((img, index) => (
                <span
                  key={index}
                  className="text-xs bg-white px-2 py-1 rounded-md border border-purple-200 flex items-center gap-1"
                >
                  🖼️ {img.name.substring(0, 15)}...
                  <button
                    type="button"
                    onClick={() => removeFile("image", index)}
                    className="text-red-500 ml-1"
                  >
                    ×
                  </button>
                </span>
              ))}
              {champs.files.map((f, index) => (
                <span
                  key={index}
                  className="text-xs bg-white px-2 py-1 rounded-md border border-blue-200 flex items-center gap-1"
                >
                  📁 {f.name.substring(0, 15)}...
                  <button
                    type="button"
                    onClick={() => removeFile("files", index)}
                    className="text-red-500 ml-1"
                  >
                    ×
                  </button>
                </span>
              ))}
            </div>
          )}

          <div className="flex justify-between items-center border-t pt-4">
            <div className="flex gap-4">
              <input
                type="file"
                name="image"
                accept="image/*"
                multiple
                className="hidden"
                ref={imageInputRef}
                onChange={handleFileChange}
              />
              <input
                type="file"
                name="files"
                multiple
                className="hidden"
                ref={fileInputRef}
                onChange={handleFileChange}
              />

              <button
                type="button"
                onClick={() => imageInputRef.current.click()}
                className="flex items-center gap-2 text-gray-500 hover:text-purple-600 transition-colors text-sm font-medium"
              >
                <ImageIcon size={20} />
                Images
              </button>

              <button
                type="button"
                onClick={() => fileInputRef.current.click()}
                className="flex items-center gap-2 text-gray-500 hover:text-blue-600 transition-colors text-sm font-medium"
              >
                <Paperclip size={20} />
                Documents
              </button>
            </div>

            <button
              type="submit"
              className="flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-500 text-white px-6 py-2.5 rounded-xl font-semibold hover:shadow-lg hover:opacity-90 transition-all active:scale-95"
            >
              <Send size={18} />
              Publier
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

export default PublierPost;
