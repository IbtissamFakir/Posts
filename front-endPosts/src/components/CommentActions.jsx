import React from "react";
import { Pencil, Trash2, Flag } from "lucide-react";

function CommentActions({
  isOwner,
  onEdit,
  onDelete,
  onSignal,
}) {
  return (
    <div className="flex items-center gap-1">
      {isOwner ? (
        <>
          <button
            onClick={onEdit}
            className="p-2 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
            title="Modifier"
          >
            <Pencil size={16} />
          </button>

          <button
            onClick={onDelete}
            className="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors"
            title="Supprimer"
          >
            <Trash2 size={16} />
          </button>
        </>
      ) : (
        <button
          onClick={onSignal}
          className="p-2 rounded-lg text-gray-400 hover:text-orange-600 hover:bg-orange-50 transition-colors"
          title="Signaler"
        >
          <Flag size={16} />
        </button>
      )}
    </div>
  );
}

export default CommentActions;