import React, { useRef, useState } from "react";
import { Image as ImageIcon, Paperclip, Send, X, Plus } from "lucide-react";

function PublierPost() {
  const [champs, setChamps] = useState({
    titre: "",
    content: "",
    image: [],
    files: [],
  });

  const imageInputRef = useRef(null);
  const fileInputRef = useRef(null);

  function handleChange(e) {
    const { name, value } = e.target;
    setChamps((prev) => ({ ...prev, [name]: value }));
  }

  function handleFileChange(e) {
    const { name, files } = e.target;
    setChamps((prev) => ({
      ...prev,
      [name]: [...prev[name], ...Array.from(files)],
    }));
  }

  function removeFile(type, index) {
    setChamps((prev) => ({
      ...prev,
      [type]: prev[type].filter((_, i) => i !== index),
    }));
  }

  async function handleSubmit(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append("titre", champs.titre);
    formData.append("content", champs.content);
    formData.append("user_id", 1);
    champs.image.forEach((img) => formData.append("images[]", img));
    champs.files.forEach((file) => formData.append("attachments[]", file));

    try {
      const response = await fetch("http://127.0.0.1:8000/api/posts", {
        method: "POST",
        headers: { Accept: "application/json" },
        body: formData,
      });

      if (response.ok) {
        alert("Publication réussie !");
        setChamps({ titre: "", content: "", image: [], files: [] });
      }
    } catch (error) {
      alert("Erreur lors de l'envoi");
    }
  }

  return (
    <div className="relative group w-full max-w-2xl mx-auto mb-12">
      {/* L'ombre magique en arrière-plan (Aura) */}
      <div className="absolute -inset-1 bg-gradient-to-r from-blue-100 to-purple-100 rounded-[2.5rem] blur-2xl opacity-50 group-hover:opacity-100 transition-opacity duration-700"></div>

      {/* Le conteneur principal */}
      <form
        onSubmit={handleSubmit}
        className="relative bg-white/80 backdrop-blur-xl border border-white p-8 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] transition-all duration-300"
      >
        <div className="mb-6">
          <h2 className="text-2xl font-black text-slate-800 tracking-tight">
            Nouvelle <span className="text-blue-600">Publication</span>
          </h2>
          <div className="h-1 w-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full mt-1"></div>
        </div>

        {/* Input Titre Style "Canvas" */}
        <input
          type="text"
          placeholder="Donnez un titre captivant..."
          onChange={handleChange}
          name="titre"
          value={champs.titre}
          required
          className="w-full bg-transparent text-xl font-semibold text-slate-800 placeholder-slate-300 outline-none mb-4"
        />

        {/* Textarea Style "Canvas" */}
        <textarea
          placeholder="Quoi que neuf?"
          onChange={handleChange}
          name="content"
          value={champs.content}
          required
          className="w-full bg-transparent text-slate-600 placeholder-slate-300 text-lg h-32 resize-none outline-none leading-relaxed"
        ></textarea>

        {/* Affichage des fichiers (Modern Chips) */}
        {(champs.image.length > 0 || champs.files.length > 0) && (
          <div className="flex flex-wrap gap-3 mb-6 p-2">
            {champs.image.map((img, index) => (
              <div key={index} className="group/item relative bg-blue-50/50 border border-blue-100 pl-2 pr-8 py-2 rounded-2xl flex items-center gap-2 animate-in zoom-in-95 duration-300">
                <span className="text-blue-500">🖼️</span>
                <span className="text-xs font-bold text-blue-700 max-w-[120px] truncate">{img.name}</span>
                <button
                  type="button"
                  onClick={() => removeFile("image", index)}
                  className="absolute right-2 p-1 rounded-full bg-blue-100 text-blue-600 hover:bg-red-500 hover:text-white transition-all"
                >
                  <X size={12} />
                </button>
              </div>
            ))}
            {champs.files.map((f, index) => (
              <div key={index} className="group/item relative bg-slate-50/50 border border-slate-200 pl-2 pr-8 py-2 rounded-2xl flex items-center gap-2 animate-in zoom-in-95 duration-300">
                <span className="text-slate-500">📁</span>
                <span className="text-xs font-bold text-slate-700 max-w-[120px] truncate">{f.name}</span>
                <button
                  type="button"
                  onClick={() => removeFile("files", index)}
                  className="absolute right-2 p-1 rounded-full bg-slate-200 text-slate-600 hover:bg-red-500 hover:text-white transition-all"
                >
                  <X size={12} />
                </button>
              </div>
            ))}
          </div>
        )}

        {/* Barre d'action basse */}
        <div className="flex items-center justify-between pt-6 border-t border-slate-50">
          <div className="flex gap-2">
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
              className="p-3 bg-slate-50 text-slate-500 hover:bg-blue-50 hover:text-blue-600 rounded-2xl transition-all duration-300"
              title="Ajouter des images"
            >
              <ImageIcon size={22} />
            </button>

            <button
              type="button"
              onClick={() => fileInputRef.current.click()}
              className="p-3 bg-slate-50 text-slate-500 hover:bg-purple-50 hover:text-purple-600 rounded-2xl transition-all duration-300"
              title="Ajouter des fichiers"
            >
              <Paperclip size={22} />
            </button>
          </div>

          <button
            type="submit"
            className="flex items-center gap-3 px-4 py-2 bg-blue-600 text-white rounded-[1.5rem] font-bold text-sm shadow-xl shadow-slate-200 hover:bg-blue-800 hover:shadow-blue-200 transition-all duration-500 active:scale-95"
          >
            <span>Publier</span>
            <div className="bg-white/20 p-1.5 rounded-lg">
              <Send size={16} className="rotate-[-10deg]" />
            </div>
          </button>
        </div>
      </form>
    </div>
  );
}

export default PublierPost;