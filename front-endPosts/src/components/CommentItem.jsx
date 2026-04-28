import React, { useState } from "react";
import toast from "react-hot-toast";
import CommentActions from "./CommentActions";

function CommentItem({ data, onDelete, onUpdate, onSignal }) {
  const [isEditing, setIsEditing] = useState(false);
  const [tempContent, setTempContent] = useState(data.content || "");

  const [showSignal, setShowSignal] = useState(false);
  const [description, setDescription] = useState("");
 //vrai user 
 //const currentUserId = JSON.parse(localStorage.getItem("user"))?.id;


 //pour tester 
  const currentUserId = 1;
  const isOwner = Number(currentUserId) === Number(data.user_id);
  console.log("USER LOCAL:", currentUserId);
  console.log("COMMENT USER:", data.user_id);
  // 🔹 INITIALS AVATAR (STYLE PRO)
  function getInitials(name = "") {
    return (
      name
        .split(" ")
        .filter(Boolean)
        .map((w) => w[0])
        .slice(0, 2)
        .join("")
        .toUpperCase() || "?"
    );
  }

  // 🔹 DATE FORMAT
  function formatDate(date) {
    if (!date) return "à l'instant";

    const diff = Date.now() - new Date(date);
    const min = Math.floor(diff / 60000);
    const hr = Math.floor(diff / 3600000);
    const d = Math.floor(diff / 86400000);

    if (min < 1) return "à l'instant";
    if (min < 60) return `il y a ${min} min`;
    if (hr < 24) return `il y a ${hr} h`;
    return `il y a ${d} jour${d > 1 ? "s" : ""}`;
  }

  // UPDATE
  const handleSave = () => {
    if (!tempContent.trim()) {
      toast.error("Veuillez écrire un commentaire");
      return;
    }

    onUpdate(data.id, tempContent.trim());
    setIsEditing(false);
    toast.success("Commentaire mis à jour");
  };

  // DELETE
  const handleDelete = () => {
    toast.custom((t) => (
      <div className="bg-white shadow-xl rounded-xl p-4 w-[300px] border">
        <p className="text-sm font-medium text-gray-800">
          Supprimer ce commentaire ?
        </p>

        <div className="flex justify-end gap-2 mt-3">
          <button
            onClick={() => toast.dismiss(t.id)}
            className="px-3 py-1 text-sm rounded-md bg-gray-100"
          >
            Annuler
          </button>

          <button
            onClick={() => {
              onDelete(data.id);
              toast.dismiss(t.id);
              toast.success("Commentaire supprimé");
            }}
            className="px-3 py-1 text-sm rounded-md bg-red-600 text-white"
          >
            Supprimer
          </button>
        </div>
      </div>
    ));
  };

  // SIGNAL
  const sendSignal = () => {
    if (!description.trim()) {
      toast.error("Veuillez écrire une raison");
      return;
    }

    onSignal(data.id, description);
    setShowSignal(false);
    setDescription("");
  };

  return (
    <div className="p-5 border-b border-gray-100 hover:bg-gray-50 transition">

      {/* HEADER */}
      <div className="flex gap-3">

        {/* AVATAR */}
        {data.user?.photo ? (
          <img
            src={`http://127.0.0.1:8000/storage/posts/images/${data.user.photo}`}
            className="w-10 h-10 rounded-full object-cover border"
            alt="avatar"
          />
        ) : (
          <div className="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">
            {getInitials(data.user?.nom_complet)}
          </div>
        )}

        {/* CONTENT */}
        <div className="flex-1">

          {/* NAME + DATE */}
          <div>
            <h4 className="font-semibold text-sm text-gray-900">
              {data.user?.nom_complet || "Utilisateur"}
            </h4>
            <p className="text-xs text-gray-400">
              {formatDate(data.created_at)}
            </p>
          </div>

          {/* TEXT OR EDIT */}
          {!isEditing ? (
            <p className="text-sm text-gray-700 mt-1 whitespace-pre-wrap">
              {data.content}
            </p>
          ) : (
            <textarea
              value={tempContent}
              onChange={(e) => setTempContent(e.target.value)}
              className="w-full border p-2 text-sm rounded-md mt-2"
            />
          )}

        </div>

        {/* ACTIONS */}
        {!isEditing && (
          <CommentActions
            isOwner={isOwner}
            onEdit={() => setIsEditing(true)}
            onDelete={handleDelete}
            onSignal={() => setShowSignal(true)}
          />
        )}
      </div>

      {/* EDIT BUTTONS */}
      {isEditing && (
        <div className="flex justify-end gap-2 mt-2">
          <button
            onClick={() => setIsEditing(false)}
            className="text-gray-500 text-sm"
          >
            Annuler
          </button>

          <button
            onClick={handleSave}
            className="bg-black text-white px-3 py-1 rounded-md text-sm"
          >
            Enregistrer
          </button>
        </div>
      )}

      {/* SIGNAL MODAL */}
      {showSignal && (
        <div className="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

          <div className="bg-white p-5 rounded-xl w-[350px]">

            <h2 className="font-bold mb-2">Signaler commentaire</h2>

            <textarea
              className="w-full border p-2 text-sm rounded"
              placeholder="Pourquoi signalez-vous ce commentaire ?"
              value={description}
              onChange={(e) => setDescription(e.target.value)}
            />

            <div className="flex justify-end gap-2 mt-3">

              <button
                onClick={() => setShowSignal(false)}
                className="px-3 py-1 text-gray-600"
              >
                Annuler
              </button>

              <button
                onClick={sendSignal}
                className="bg-red-600 text-white px-3 py-1 rounded"
              >
                Envoyer
              </button>

            </div>

          </div>

        </div>
      )}

    </div>
  );
}

export default CommentItem;