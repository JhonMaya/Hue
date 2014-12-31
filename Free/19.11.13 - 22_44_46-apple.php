<?php exit() ?>--by apple 84.107.12.248
require "Prodiction"

function GetHitBoxRadius(target, d)
	d = d or 0
	if ValidTarget(target) then
		return (target.collisionRadius-2)/2 + d
	end
	return 0
end

local function pnt(vec)
	local vx = WorldToScreen(D3DXVECTOR3(vec.x, myHero.y, vec.z))
	
	return Point(vx.x, vx.y)
end

local function extend(v,amt,f)
	f = f or myHero
	
	return Vector(v) + (Vector(v) - Vector(f)):normalized() * (amt)
end

local function extend2(v,amt,f)
	f = f or myHero
	return Vector(v) + (Vector(v) - Vector(f)):perpendicular():normalized() * (amt)	
end

local function COL_GenColMap(minions, prediction, from)
	local d = prediction.Spell.width
	local map = {}
	for indx, minion in pairs(minions) do
		local mhbr = GetHitBoxRadius(minion, d)
		local mpos = prediction:GetPrediction(minion)
		if mpos ~= nil then
			local mp1 = extend(extend2(mpos,mhbr, from),-mhbr, from)
			local mp2 = extend(extend2(mpos,-mhbr, from),-mhbr, from)
			local mp3 = extend(mp1, 2000, from)
			local mp4 = extend(mp2, 2000, from)
			local poly = Polygon(pnt(mp3),pnt(mp1), pnt(mp2),pnt(mp4))
			table.insert(map, poly)
		end
	end
	
	return map
end

local function COL_IsInColMap(map, p)
	for i = 1, #map do
		local v = map[i]
		if v:contains(pnt(p)) then
			return true
		end
	end
	
	return false
end

local function COL_DrawMap(map)
	for i = 1, #map do
		local v = map[i]:getPoints()
		local prev = nil
		for n, b in pairs(v) do
			if prev ~= nil then
				DrawLine(prev.x, prev.y,b.x, b.y, 2, 2684354716)
			end
			
			prev = b
		end
	end
	
	if IsInColMap(map, mousePos) then 
		DrawCircle(mousePos.x, mousePos.y, mousePos.z, 50, 0x7F006E)
	end
end

local COL_Instances = {}
local COL_Init = false
local COL_Minions = minionManager(MINION_ENEMY, 2000, myHero, MINION_SORT_HEALTH_ASC)
local COL_DefaultFunc = function() return myHero end

class "FastCol"

function FastCol:__init(prediction, fromFunc)
	self.fromFunc = fromFunc or COL_DefaultFunc
	self.colmap = nil
	self.prediction = prediction
	table.insert(COL_Instances, self)
	
	if not COL_Init then
		AddTickCallback(function()
			COL_Minions:update()
			for _, instance in ipairs(COL_Instances) do
				instance.colmap = COL_GenColMap(COL_Minions.objects, self.prediction, self.fromFunc())
			end
		end)
		COL_Init = true
	end
end

function FastCol:DrawCollisionPaths()
	COL_DrawMap(self.colmap)
end

function FastCol:GetMinionCollision(pos)
	return COL_IsInColMap(self.colmap, pos)
end