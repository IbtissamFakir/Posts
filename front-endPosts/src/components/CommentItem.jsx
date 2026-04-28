import React, { useState } from "react";
import toast from "react-hot-toast";
import CommentActions from "./CommentActions";

function CommentItem({ data, onDelete, onUpdate, onSignal }) {
  const [isEditing, setIsEditing] = useState(false);
  const [tempContent, setTempContent] = useState(data.content || "");

  const [showSignal, setShowSignal] = useState(false);
  const [description, setDescription] = useState("");
  
  const currentUserId = 1;
  const isOwner = Number(currentUserId) === Number(data.user_id);

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
      <div className="bg-white shadow-xl rounded-xl p-4 w-[300px] border border-gray-200">
        <p className="text-sm font-medium text-gray-800">
          Supprimer ce commentaire ?
        </p>

        <div className="flex justify-end gap-2 mt-3">
          <button
            onClick={() => toast.dismiss(t.id)}
            className="px-3 py-1 text-sm rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors"
          >
            Annuler
          </button>

          <button
            onClick={() => {
              onDelete(data.id);
              toast.dismiss(t.id);
              toast.success("Commentaire supprimé");
            }}
            className="px-3 py-1 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors"
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
    <div className="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">

      {/* HEADER */}
      <div className="flex gap-3">

        {/* AVATAR */}
        {data.user?.photo ? (
          <img
            src={`http://127.0.0.1:8000/storage/posts/images/${data.user.photo}`}
            className="w-9 h-9 rounded-full object-cover border-2 border-gray-100"
            alt="avatar"
          />
        ) : (
          <div className="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
            {getInitials(data.user?.nom_complet)}
          </div>
        )}

        {/* CONTENT */}
        <div className="flex-1">

          {/* NAME + DATE */}
          <div className="flex items-center gap-2">
            <h4 className="font-semibold text-sm text-gray-900">
              {data.user?.nom_complet || "Utilisateur"}
            </h4>
            <p className="text-xs text-gray-500">
              {formatDate(data.created_at)}
            </p>
          </div>

          {/* TEXT OR EDIT */}
          {!isEditing ? (
            <p className="text-sm text-gray-700 mt-1 whitespace-pre-wrap leading-relaxed">
              {data.content}
            </p>
          ) : (
            <textarea
              value={tempContent}
              onChange={(e) => setTempContent(e.target.value)}
              className="w-full border border-gray-300 p-2 text-sm rounded-lg mt-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 resize-none"
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
        <div className="flex justify-end gap-2 mt-3 pl-12">
          <button
            onClick={() => setIsEditing(false)}
            className="text-gray-600 text-sm hover:text-gray-800 font-medium"
          >
            Annuler
          </button>

          <button
            onClick={handleSave}
            className="bg-blue-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-700 font-medium transition-colors"
          >
            Enregistrer
          </button>
        </div>
      )}

      {/* SIGNAL MODAL */}
      {showSignal && (
        <div className="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">

          <div className="bg-white p-6 rounded-xl w-full max-w-sm shadow-2xl border border-gray-200">

            <h2 className="font-bold mb-3 text-gray-900 text-lg">Signaler ce commentaire</h2>

            <textarea
              className="w-full border border-gray-300 p-3 text-sm rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 resize-none"
              placeholder="Expliquez pourquoi vous signalez ce commentaire..."
              value={description}
              onChange={(e) => setDescription(e.target.value)}
              rows={4}
            />

            <div className="flex justify-end gap-2 mt-4">

              <button
                onClick={() => setShowSignal(false)}
                className="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg font-medium transition-colors"
              >
                Annuler
              </button>

              <button
                onClick={sendSignal}
                className="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors"
              >
                Signaler
              </button>

            </div>

          </div>

        </div>
      )}

    </div>
  );
}

export default CommentItem;