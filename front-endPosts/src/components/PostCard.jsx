import React, { useState } from "react";
import axios from "axios";

import ChatBubbleLeftIcon from "@heroicons/react/24/outline/ChatBubbleLeftIcon";
import HeartIcon from "@heroicons/react/24/outline/HeartIcon";
import BookmarkIcon from "@heroicons/react/24/outline/BookmarkIcon";

import HeartSolid from "@heroicons/react/24/solid/HeartIcon";
import BookmarkSolid from "@heroicons/react/24/solid/BookmarkIcon";

import { FileText, FileSpreadsheet, File, FileCode } from "lucide-react";
function PostCard({ post }) {
  function formatDate(date) {
    const diff = Date.now() - new Date(date);
    const m = Math.floor(diff / 60000);
    const h = Math.floor(diff / 3600000);
    const d = Math.floor(diff / 86400000);
    const w = Math.floor(d / 7);
    const mo = Math.floor(d / 30);
    const y = Math.floor(d / 365);

    if (m < 1) return "à l'instant";
    if (m < 60) return `Il y a ${m} minute${m > 1 ? "s" : ""}`;
    if (h < 24) return `Il y a ${h} heure${h > 1 ? "s" : ""}`;
    if (d === 1) return "hier";
    if (d < 7) return `Il y a ${d} jours`;
    if (w < 4) return `Il y a ${w} semaine${w > 1 ? "s" : ""}`;
    if (mo < 12) return `Il y a ${mo} mois`;
    return `Il y a ${y} an${y > 1 ? "s" : ""}`;
  }

  function getInitials(nom_complet = "") {
    return (
      nom_complet
        .split(" ")
        .filter(Boolean)
        .map((n) => n[0])
        .slice(0, 2)
        .join("")
        .toUpperCase() || "?"
    );
  }
  const [count, setCount] = useState(post.likes_count ?? 0);
  const [liked, setLiked] = useState(post.liked ?? false);
  const [isSaved, setIsSaved] = useState(Boolean(post.is_saved));
  // Liker un Post
  function handleLiker() {
    axios
      .post(`http://127.0.0.1:8000/api/posts/${post.id}/like`)
      .then((res) => {
        setLiked(res.data.liked);
        setCount(res.data.likes_count);
      })
      .catch((err) => console.error(err));
  }
  // Enregistrer un Post
  function handleEnregistrer() {
    if (isSaved) {
      axios
        .delete(`http://127.0.0.1:8000/api/posts/${post.id}/unsave`)
        .then((res) => {
          console.log("Succès Unsave:", res.data);
          setIsSaved(false);
        })
        .catch((err) => {
          console.error(
            "Erreur Unsave détaillée:",
            err.response?.data || err.message,
          );
        });
    } else {
      axios
        .post(`http://127.0.0.1:8000/api/posts/${post.id}/save`)
        .then((res) => {
          console.log("Succès Save:", res.data);
          setIsSaved(true);
        })
        .catch((err) => {
          console.error(
            "Erreur Save détaillée:",
            err.response?.data || err.message,
          );
        });
    }
  }
  return (
    <div className="max-w-2xl w-full mx-auto bg-white rounded-2xl shadow-sm p-6 border border-gray-200 mb-8">
      <section className="flex items-center mb-4">
        {post.user?.photo ? (
          <img
            src={`http://127.0.0.1:8000/storage/posts/images/${post.user.photo}`}
            alt="profile"
            className="w-12 h-12 rounded-full object-cover border border-gray-100"
          />
        ) : (
          <div className="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
            {getInitials(post.user?.nom_complet)}
          </div>
        )}

        <div className="flex-1 ml-4">
          <h3 className="text-lg font-bold text-gray-900 leading-tight">
            {post.user?.nom_complet || "Utilisateur inconnu"}
          </h3>
          <p className="text-gray-500 text-xs">
            {formatDate(post.date_publication)}
          </p>
        </div>
      </section>

      <div className="mb-4">
        <h3 className="text-xl font-semibold text-gray-800 mb-2">
          {post.titre}
        </h3>
        <p className="text-gray-700 leading-relaxed mb-4">{post.content}</p>
        <div className="grid grid-cols-1 gap-2 mb-4">
          {post.images?.map((img, index) => (
            <img
              key={index}
              src={`http://127.0.0.1:8000/storage/${img}`}
              alt="post"
              className="w-full rounded-xl object-cover max-h-96"
            />
          ))}
        </div>
        <div className="mb-4 space-y-2 ">
          {post.fichiers?.map((file, index) => {
            const ext = file.split(".").pop().toLowerCase();
            const fileName = file.split("/").pop();

            let icon = <File className="w-5 h-5 text-gray-500 " />;

            if (ext === "pdf") {
              icon = <FileText className="w-5 h-5 text-red-500" />;
            } else if (["xls", "xlsx", "csv"].includes(ext)) {
              icon = <FileSpreadsheet className="w-5 h-5 text-green-600" />;
            } else if (["doc", "docx"].includes(ext)) {
              icon = <FileText className="w-5 h-5 text-blue-700" />;
            } else if (ext === "txt") {
              icon = <FileCode className="w-5 h-5 text-gray-600" />;
            }

            return (
              <a
                key={index}
                href={`http://127.0.0.1:8000/storage/${file}`}
                target="_blank"
                rel="noreferrer"
                className="flex items-center space-x-2 text-gray-800  bg-gray-50 hover:bg-gray-100 border border-gray-200 p-3 rounded-lg transition-colors group"
              >
                {icon}
                <span>{fileName}</span>
              </a>
            );
          })}
        </div>
        <div className="flex items-center text-sm text-gray-500 mb-2">
          <HeartSolid className="w-4 h-4 mr-1 text-red-500" />
          <span>{count}</span>
        </div>
      </div>

      <hr className="border-gray-100" />

      <div className="flex items-center justify-around mt-3">
        <button
          className={`flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors ${liked ? "text-red-500 hover:bg-red-50 " : "text-gray-600 hover:bg-gray-100 "}`}
          onClick={handleLiker}
        >
          {liked ? (
            <HeartSolid className="w-6 h-6" />
          ) : (
            <HeartIcon className="w-6 h-6" />
          )}
          <span className="font-medium text-sm">J'aime</span>
        </button>

        <button className="flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 ">
          <ChatBubbleLeftIcon className="w-6 h-6" />
          <span className="font-medium text-sm">Commenter</span>
        </button>

        <button
          onClick={handleEnregistrer}
          className={`flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors ${isSaved ? "text-blue-600 hover:bg-blue-50" : "text-gray-600 hover:bg-gray-100"}`}
        >
          {isSaved ? (
            <BookmarkSolid className="w-6 h-6" />
          ) : (
            <BookmarkIcon className="w-6 h-6" />
          )}
          <span className="font-medium text-sm">
            {isSaved ? "Enregistré" : "Enregistrer"}
          </span>
        </button>
      </div>
    </div>
  );
}

export default PostCard;
