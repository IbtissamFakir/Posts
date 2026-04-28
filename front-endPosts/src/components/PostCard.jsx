import React, { useState } from "react";
import axios from "axios";

import ChatBubbleLeftIcon from "@heroicons/react/24/outline/ChatBubbleLeftIcon";
import HeartIcon from "@heroicons/react/24/outline/HeartIcon";
import BookmarkIcon from "@heroicons/react/24/outline/BookmarkIcon";

import HeartSolid from "@heroicons/react/24/solid/HeartIcon";
import BookmarkSolid from "@heroicons/react/24/solid/BookmarkIcon";

import { FileText, FileSpreadsheet, File, FileCode } from "lucide-react";
import CommentForm from "./CommentForm";
import CommentList from "./CommentList";
import ImageCarousel from "./ImageCarousel";

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
  const [showComments, setShowComments] = useState(false);
  
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
    <div className="w-full bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow duration-200">
      {/* En-tête avec profil */}
      <section className="flex items-center mb-4">
        {post.user?.photo ? (
          <img
            src={`http://127.0.0.1:8000/storage/posts/images/${post.user.photo}`}
            alt="profile"
            className="w-12 h-12 rounded-full object-cover border-2 border-gray-100"
          />
        ) : (
          <div className="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
            {getInitials(post.user?.nom_complet)}
          </div>
        )}

        <div className="flex-1 ml-4">
          <h3 className="text-sm font-semibold text-gray-900 leading-tight">
            {post.user?.nom_complet || "Utilisateur inconnu"}
          </h3>
          <p className="text-gray-500 text-xs">
            {formatDate(post.date_publication)}
          </p>
        </div>
      </section>

      {/* Contenu du post */}
      <div className="mb-4">
        <h3 className="text-lg font-bold text-gray-900 mb-2">
          {post.titre}
        </h3>
        <p className="text-gray-700 leading-relaxed mb-4 text-sm">
          {post.content}
        </p>
        
        {/* Carrousel d'images */}
        <ImageCarousel images={post.images} />
        
        {/* Fichiers */}
        <div className="mb-4 space-y-2">
          {post.fichiers?.map((file, index) => {
            const ext = file.split(".").pop().toLowerCase();
            const fileName = file.split("/").pop();

            let icon = <File className="w-5 h-5 text-gray-500" />;

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
                className="flex items-center space-x-3 text-gray-800 bg-gray-50 hover:bg-gray-100 border border-gray-200 p-3 rounded-lg transition-colors group"
              >
                {icon}
                <span className="text-sm font-medium">{fileName}</span>
              </a>
            );
          })}
        </div>
        
        {/* Compteur de likes */}
        {count > 0 && (
          <div className="flex items-center text-sm text-gray-600 mb-3 pb-3 border-b border-gray-100">
            <HeartSolid className="w-4 h-4 mr-2 text-red-500" />
            <span className="font-medium">{count} personne{count > 1 ? "s" : ""}</span>
          </div>
        )}
      </div>

      {/* Boutons d'action */}
      <div className="flex items-center justify-around">
        <button
          className={`flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors flex-1 justify-center font-medium text-sm ${
            liked 
              ? "text-red-500 bg-red-50 hover:bg-red-100" 
              : "text-gray-600 hover:bg-gray-100"
          }`}
          onClick={handleLiker}
        >
          {liked ? (
            <HeartSolid className="w-5 h-5" />
          ) : (
            <HeartIcon className="w-5 h-5" />
          )}
          <span>J'aime</span>
        </button>

        <button
          onClick={() => setShowComments(!showComments)}
          className={`flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors flex-1 justify-center font-medium text-sm ${
            showComments
              ? "text-blue-600 bg-blue-50 hover:bg-blue-100"
              : "text-gray-600 hover:bg-gray-100"
          }`}
        >
          <ChatBubbleLeftIcon className="w-5 h-5" />
          <span>Commenter</span>
        </button>

        <button
          onClick={handleEnregistrer}
          className={`flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors flex-1 justify-center font-medium text-sm ${
            isSaved
              ? "text-blue-600 bg-blue-50 hover:bg-blue-100"
              : "text-gray-600 hover:bg-gray-100"
          }`}
        >
          {isSaved ? (
            <BookmarkSolid className="w-5 h-5" />
          ) : (
            <BookmarkIcon className="w-5 h-5" />
          )}
          <span>{isSaved ? "Enregistré" : "Enregistrer"}</span>
        </button>
      </div>
      
      {/* Section commentaires */}
      {showComments && (
        <div className="mt-4 pt-4 border-t border-gray-200 animate-in fade-in duration-300">
          <CommentForm
            postId={post.id}
            refreshComments={() => window.location.reload()}
          />
          <CommentList postId={post.id} />
        </div>
      )}
    </div>
  );
}

export default PostCard;
